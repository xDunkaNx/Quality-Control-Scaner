<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\Defect;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function exportCsv(Request $r): StreamedResponse
    {
        $filters = $r->only(['status','type_id','loc_id','date_from','date_to']);
        $filename = 'defectos_'.now()->format('Ymd_His').'.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        return response()->streamDownload(function () use ($filters) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM UTF-8
            fputcsv($out, ['Fecha','Barcode','Producto','Tipo','Ubicación','Lote','Severidad','Estado','Notas']);

            Defect::with(['product','type','location'])
                ->when($filters['status'] ?? null, fn($q,$v)=>$q->where('status',$v))
                ->when($filters['type_id'] ?? null, fn($q,$v)=>$q->where('defect_type_id',$v))
                ->when($filters['loc_id'] ?? null,  fn($q,$v)=>$q->where('location_id',$v))
                ->when($filters['date_from'] ?? null, fn($q,$v)=>$q->whereDate('found_at','>=',$v))
                ->when($filters['date_to'] ?? null,   fn($q,$v)=>$q->whereDate('found_at','<=',$v))
                ->orderBy('found_at','desc')
                ->chunk(1000, function($rows) use ($out) {
                    foreach ($rows as $d) {
                        fputcsv($out, [
                            optional($d->found_at)->format('Y-m-d H:i'),
                            $d->product->barcode ?? '',
                            $d->product->name ?? '',
                            $d->type->name ?? '',
                            $d->location->name ?? $d->location_text ?? '',
                            $d->lot ?? '',
                            // $d->severity ?? '',
                            $d->unit ?? '',
                            $d->status ?? '',
                            $d->notes ?? '',
                        ]);
                    }
                });

            fclose($out);
        }, $filename, $headers);
    }
    public function weekly(Request $request)
    {
        // Filtros opcionales
        $filters = $request->only(['status', 'type_id', 'loc_id', 'date_from', 'date_to']);

        $defects = Defect::select(
                'product_id',
                'defect_type_id',
                'location_id',
                DB::raw('COUNT(*) as total_defects')
            )
            ->with(['product', 'type', 'location'])
            ->when($filters['status'] ?? null, fn($q, $v) => $q->where('status', $v))
            ->when($filters['type_id'] ?? null, fn($q, $v) => $q->where('defect_type_id', $v))
            ->when($filters['loc_id'] ?? null, fn($q, $v) => $q->where('location_id', $v))
            ->when($filters['date_from'] ?? null, fn($q, $v) => $q->whereDate('found_at', '>=', $v))
            ->when($filters['date_to'] ?? null, fn($q, $v) => $q->whereDate('found_at', '<=', $v))
            ->groupBy('product_id','defect_type_id','location_id')
            ->orderBy('total_defects','desc')
            ->paginate(50)
            ->appends($request->query());

        $types = \App\Models\DefectType::orderBy('name')->get();
        $locs  = \App\Models\Location::orderBy('name')->get();

        return view('reports.defects.weekly', compact('defects','types','locs'));
    }
}
