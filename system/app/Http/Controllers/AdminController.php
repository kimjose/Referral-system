<?php

namespace App\Http\Controllers;

use App\Models\m_f_l_s;
use App\Models\Patient;
use App\Models\Referral;
use App\Models\TestUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use Charts;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        // ... existing code ...
    }

    public function testCharts(){

//        HANDLING TEST USERS DATA

        $groups = TestUser::select('age', DB::raw('count(*) as total'))
            ->groupBy('age')
            ->pluck('total', 'age')
            ->all();
        // Generate random colours for the groups
        for ($i=0; $i<=count($groups); $i++) {
            $colours[] = '#' . substr(str_shuffle('ABCDEF0123456789'), 0, 6);
        }
        // Prepare the data for returning with the view
        $chart = new Chart();
        $chart->labels = (array_keys($groups));
        $chart->dataset = (array_values($groups));
        $chart->colours = $colours;




//        HANDLING DATA FOR THE REFERRALS

        $from = '2023-05-01';
        $to = '2023-08-01';

        $referrals = Referral::whereBetween('created_at', [$from, $to])
            ->groupBy('referredFacility')
            ->select('referredFacility', DB::raw('COUNT(*) as count'))
            ->get();

        $facilityNames = $referrals->pluck('referredFacility');
        $referralCounts = $referrals->pluck('count');

        $chart2 = new Chart();
        $chart2->facilityNames = $facilityNames;
        $chart2->referralCounts = $referralCounts;



        //HANDLING STATUS FOR THE REFERRALS

        $statusCounts = Referral::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();


        //LINE CHART
        $referrals = Referral::all();

        $referralCounts = $referrals->groupBy(function ($referral) {
            return $referral->created_at->format('F Y');
        })->map(function ($group) {
            return $group->count();
        });

        $chartData = [
            'labels' => $referralCounts->keys()->toArray(),
            'data' => $referralCounts->values()->toArray(),
        ];


//        dd($chartData);


        return view('visualizations.test-charts')->with(['chart' => $chart,
            'referralChart' => $chart2, 'statusData' => $statusCounts, 'chartData' => $chartData]);
    }

    public function charts(){

        $data = Referral::select('id', 'created_at')->get()->groupBy(function ($data){
            return Carbon::parse($data->created_at)->format('M');
        });

        $months = [];
        $monthCount = [];
        foreach ($data as $month => $values){

            $months[] = $month;
            $monthCount[] = count($values);
        }

        //dd($data);

        return view('visualizations.charts')->with([
            'data'=> $data,
            'month' => $months,
            'monthCount' => $monthCount]);

    }






    public function admin(){

        //BAR GRAPH
        $data = Referral::whereBetween('created_at', [now()->subDays(7), now()])
            ->groupBy('fhir_status')
            ->groupByRaw('DATE(created_at)') // Group by the same day of the 'created_at' timestamp
            ->selectRaw('fhir_status, DATE(created_at) AS date, count(*) as count')
            ->get();

        // Create the line chart
        $chart = new Chart;

        if ($data->isNotEmpty()) {
            // Prepare the chart data
            $chartData = [];
            foreach ($data as $item) {
                $chartData[$item->date][$item->fhir_status] = $item->count;
            }

            $chart->labels(array_keys($chartData));
            foreach ($data->pluck('fhir_status')->unique() as $status) {
                $color = '';
                if ($status === 'Rejected') {
                    $color = 'rgba(255, 206, 86, 0.2)';
                } elseif ($status === 'Accepted') {
                    $color = 'rgba(75, 192, 192, 0.2)';
                } elseif ($status === 'Pending') {
                    $color = 'rgba(255, 99, 132, 0.2)';
                }

                $chart->dataset($status, 'bar', collect($chartData)->pluck($status)->toArray())
                    ->color($color)
                    ->backgroundColor($color);
            }
        } else {
            $chart->labels(['No Data']);
            $chart->dataset('No data available for the last 7 days', 'bar', [0]);
        }

        $chart->title('Referral Status');
        $chart->options([
            'responsive' => true,
        ]);




        //PIE CHART
        $pieChart = Referral::whereBetween('created_at', [now()->subMonth(1), now()])
            ->selectRaw('fhir_status, count(*) as count')
            ->groupBy('fhir_status')
            ->get();

        $acceptedRejectedCount = 0;
        $pendingReferralsCount = 0;
        foreach ($pieChart as $item) {
            if ($item->fhir_status === 'Accepted' || $item->fhir_status === 'Rejected') {
                $acceptedRejectedCount += $item->count;
            } elseif ($item->fhir_status === 'Pending') {
                $pendingReferralsCount += $item->count;
            }
        }
        $completedCount = $acceptedRejectedCount;

        $pieChartData = [
            'Complete' => $completedCount,
            'Pending' => $pendingReferralsCount,
        ];

        $pieChartLabels = array_keys($pieChartData);


        // Create the pie chart
        $completedPieChart = new Chart;
        $completedPieChart->labels($pieChartLabels);

        $completedPieChart->dataset('Status', 'pie', array_values($pieChartData))
            ->color([
                'rgba(255, 206, 86, 0.2)',
                'rgba(255, 99, 132, 0.2)',
            ])
            ->backgroundColor([
                'rgba(255, 206, 86, 0.2)',
                'rgba(255, 99, 132, 0.2)',
            ]);


        //PIE CHART2
        $referralsStatusPieChart = Referral::whereBetween('created_at', [now()->subMonth(1), now()])
            ->selectRaw('fhir_status, count(*) as count')
            ->groupBy('fhir_status')
            ->get();

        $acceptedReferralsCount = 0;
        $pendingReferralsCount = 0;
        $rejectedReferralsCount = 0;

        foreach ($referralsStatusPieChart as $item) {
            if ($item->fhir_status === 'Accepted') {
                $acceptedReferralsCount += $item->count;
            } elseif ($item->fhir_status === 'Pending') {
                $pendingReferralsCount += $item->count;
            } elseif ($item->fhir_status === 'Rejected') {
                $rejectedReferralsCount += $item->count;
            }
        }

        $pieChartData2 = [
            'Accepted' => $acceptedReferralsCount,
            'Pending' => $pendingReferralsCount,
            'Rejected' => $rejectedReferralsCount,
        ];


        // Create the pie chart
        $completedPieChart2 = new Chart;
        $completedPieChart2->labels(array_keys($pieChartData2));
        $completedPieChart2->dataset('Referral Status', 'pie', array_values($pieChartData2))
            ->color([
                'rgba(75, 192, 192, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 99, 132, 0.2)',
            ])
            ->backgroundColor([
                'rgba(75, 192, 192, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(255, 99, 132, 1)',
            ]);
        $completedPieChart2->title('Referral Status Distribution');


        $patientsCount = Patient::count();
        $physiciansCount = User::whereHas('roles', function ($query) {
            $query->where('name', 'doctor');
        })->count();
        $referralsCount = Referral::count();
        $facilities = m_f_l_s::count();

        return view('admin.index', compact('patientsCount', 'physiciansCount', 'referralsCount', 'facilities', 'chart', 'pieChartData', 'pieChartLabels', 'completedPieChart', 'completedPieChart2'));
    }

    public function visualizations()
    {
        //BAR GRAPH
        $data = Referral::whereBetween('created_at', [now()->subDays(7), now()])
            ->groupBy('fhir_status')
            ->groupByRaw('DATE(created_at)') // Group by the same day of the 'created_at' timestamp
            ->selectRaw('fhir_status, DATE(created_at) AS date, count(*) as count')
            ->get();

        // Create the line chart
        $chart = new Chart;

        if ($data->isNotEmpty()) {
            // Prepare the chart data
            $chartData = [];
            foreach ($data as $item) {
                $chartData[$item->date][$item->fhir_status] = $item->count;
            }

            $chart->labels(array_keys($chartData));
            foreach ($data->pluck('fhir_status')->unique() as $status) {
                $color = '';
                if ($status === 'Rejected') {
                    $color = 'rgba(255, 206, 86, 0.2)';
                } elseif ($status === 'Accepted') {
                    $color = 'rgba(75, 192, 192, 0.2)';
                } elseif ($status === 'Pending') {
                    $color = 'rgba(255, 99, 132, 0.2)';
                }

                $chart->dataset($status, 'bar', collect($chartData)->pluck($status)->toArray())
                    ->color($color)
                    ->backgroundColor($color);
            }
        } else {
            $chart->labels(['No Data']);
            $chart->dataset('No data available for the last 7 days', 'bar', [0]);
        }

        $chart->title('Referral Status');
        $chart->options([
            'responsive' => true,
        ]);




        //PIE CHART
        $pieChart = Referral::whereBetween('created_at', [now()->subMonth(1), now()])
            ->selectRaw('fhir_status, count(*) as count')
            ->groupBy('fhir_status')
            ->get();

        $acceptedRejectedCount = 0;
        $pendingReferralsCount = 0;
        foreach ($pieChart as $item) {
            if ($item->fhir_status === 'Accepted' || $item->fhir_status === 'Rejected') {
                $acceptedRejectedCount += $item->count;
            } elseif ($item->fhir_status === 'Pending') {
                $pendingReferralsCount += $item->count;
            }
        }
        $completedCount = $acceptedRejectedCount;

        $pieChartData = [
            'Complete' => $completedCount,
            'Pending' => $pendingReferralsCount,
        ];

        $pieChartLabels = array_keys($pieChartData);


        // Create the pie chart
        $completedPieChart = new Chart;
        $completedPieChart->labels($pieChartLabels);

        $completedPieChart->dataset('Status', 'pie', array_values($pieChartData))
            ->color([
                'rgba(255, 206, 86, 0.2)',
                'rgba(255, 99, 132, 0.2)',
            ])
            ->backgroundColor([
                'rgba(255, 206, 86, 0.2)',
                'rgba(255, 99, 132, 0.2)',
            ]);


        //PIE CHART2
        $referralsStatusPieChart = Referral::whereBetween('created_at', [now()->subMonth(1), now()])
            ->selectRaw('fhir_status, count(*) as count')
            ->groupBy('fhir_status')
            ->get();

        $acceptedReferralsCount = 0;
        $pendingReferralsCount = 0;
        $rejectedReferralsCount = 0;

        foreach ($referralsStatusPieChart as $item) {
            if ($item->fhir_status === 'Accepted') {
                $acceptedReferralsCount += $item->count;
            } elseif ($item->fhir_status === 'Pending') {
                $pendingReferralsCount += $item->count;
            } elseif ($item->fhir_status === 'Rejected') {
                $rejectedReferralsCount += $item->count;
            }
        }

        $pieChartData2 = [
            'Accepted' => $acceptedReferralsCount,
            'Pending' => $pendingReferralsCount,
            'Rejected' => $rejectedReferralsCount,
        ];


        // Create the pie chart
        $completedPieChart2 = new Chart;
        $completedPieChart2->labels(array_keys($pieChartData2));
        $completedPieChart2->dataset('Referral Status', 'pie', array_values($pieChartData2))
            ->color([
                'rgba(75, 192, 192, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 99, 132, 0.2)',
            ])
            ->backgroundColor([
                'rgba(75, 192, 192, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(255, 99, 132, 1)',
            ]);
        $completedPieChart2->title('Referral Status Distribution');

        return view('visualizations.visualizations', compact('chart', 'completedPieChart', 'completedPieChart2'));
    }

    public function aggregateReport(Request $request)
    {
        $fromDate = $request->input('from_date', Carbon::now()->subDays(30)->toDateString());
        $toDate = $request->input('to_date', Carbon::now()->toDateString());

        $referralsQuery = Referral::whereBetween('referrals.created_at', [$fromDate, $toDate]);

        $referralsByStatus = (clone $referralsQuery)
            ->selectRaw('fhir_status, count(*) as count')
            ->groupBy('fhir_status')
            ->get();

        $referralsByFacility = (clone $referralsQuery)
            ->join('m_f_l_s', 'referrals.referredFacility', '=', 'm_f_l_s.Code')
            ->selectRaw('m_f_l_s.Officialname, count(referrals.id) as count')
            ->groupBy('m_f_l_s.Officialname')
            ->get();

        $referralsByService = (clone $referralsQuery)
            ->selectRaw('service, count(*) as count')
            ->groupBy('service')
            ->having('service', '!=', null)
            ->get();

        if ($request->has('export') && $request->get('export') === 'csv') {
            $reportType = $request->get('report_type', 'status');
            $fileName = "aggregate_report_by_{$reportType}.csv";

            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];

            $dataToExport = [];
            $columns = [];

            switch ($reportType) {
                case 'facility':
                    $columns = ['Facility', 'Count'];
                    $dataToExport = $referralsByFacility;
                    break;
                case 'service':
                    $columns = ['Service', 'Count'];
                    $dataToExport = $referralsByService;
                    break;
                case 'status':
                default:
                    $columns = ['Status', 'Count'];
                    $dataToExport = $referralsByStatus;
                    break;
            }

            $callback = function() use($dataToExport, $columns, $reportType) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);

                foreach ($dataToExport as $row) {
                    $rowData = [];
                    switch ($reportType) {
                        case 'facility':
                            $rowData = [$row->Officialname, $row->count];
                            break;
                        case 'service':
                            $rowData = [$row->service, $row->count];
                            break;
                        case 'status':
                        default:
                            $rowData = [$row->fhir_status, $row->count];
                            break;
                    }
                    fputcsv($file, $rowData);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return view('reports.aggregate', compact(
            'referralsByStatus',
            'referralsByFacility',
            'referralsByService',
            'fromDate',
            'toDate'
        ));
    }

    public function disaggregateReport(Request $request)
    {
        return $this->getDisaggregateReport($request);
    }

    public function lineList(Request $request, $status)
    {
        $request->merge(['status' => $status]);
        $pageTitle = \Illuminate\Support\Str::ucfirst($status) . ' Referrals Report';

        return $this->getDisaggregateReport($request, $pageTitle);
    }

    private function getDisaggregateReport(Request $request, $pageTitle = 'Disaggregate Report')
    {
        $query = Referral::query();

        if ($request->filled('from_date')) {
            $query->where('referrals.created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->where('referrals.created_at', '<=', $request->to_date);
        }
        if ($request->filled('status')) {
            $query->where('fhir_status', $request->status);
        }
        if ($request->filled('facility_id')) {
            $query->where('referredFacility', $request->facility_id);
        }

        if ($request->has('export') && $request->get('export') === 'csv') {
            $referralsToExport = $query->with('referredToFacility')->get();
            $fileName = 'disaggregate_referral_report.csv';
            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];

            $columns = ['Referral ID', 'Client UPI', 'Referring Officer', 'Referred To Facility', 'Service', 'Status', 'Date'];

            $callback = function() use($referralsToExport, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);

                foreach ($referralsToExport as $referral) {
                    $row = [
                        $referral->referralId,
                        $referral->clientUPI,
                        $referral->referringOfficer,
                        $referral->referredToFacility->Officialname ?? $referral->referredFacility,
                        $referral->service,
                        $referral->fhir_status,
                        $referral->created_at->format('Y-m-d'),
                    ];
                    fputcsv($file, $row);
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }

        $referrals = $query->with('referredToFacility')->latest()->paginate(20);

        $statuses = Referral::distinct()->pluck('fhir_status')->filter()->sort();
        $facilities = m_f_l_s::orderBy('Officialname')->get(['Code', 'Officialname']);

        return view('reports.disaggregate', [
            'referrals' => $referrals,
            'statuses' => $statuses,
            'facilities' => $facilities,
            'filters' => $request->all(),
            'pageTitle' => $pageTitle,
        ]);
    }

    public function turnaroundTimeReport(Request $request)
    {
        $fromDate = $request->input('from_date', Carbon::now()->subDays(30)->toDateString());
        $toDate = $request->input('to_date', Carbon::now()->toDateString());

        $baseQuery = Referral::whereIn('fhir_status', ['Accepted', 'Rejected'])
            ->whereBetween('referrals.updated_at', [$fromDate, $toDate]);

        $tatByFacility = (clone $baseQuery)
            ->join('m_f_l_s', 'referrals.referredFacility', '=', 'm_f_l_s.Code')
            ->select(
                'm_f_l_s.Officialname',
                DB::raw('AVG(TIMESTAMPDIFF(SECOND, referrals.created_at, referrals.updated_at)) as avg_tat_seconds'),
                DB::raw('MIN(TIMESTAMPDIFF(SECOND, referrals.created_at, referrals.updated_at)) as min_tat_seconds'),
                DB::raw('MAX(TIMESTAMPDIFF(SECOND, referrals.created_at, referrals.updated_at)) as max_tat_seconds'),
                DB::raw('COUNT(referrals.id) as count')
            )
            ->groupBy('m_f_l_s.Officialname')
            ->get();

        $tatByService = (clone $baseQuery)
            ->select(
                'service',
                DB::raw('AVG(TIMESTAMPDIFF(SECOND, created_at, updated_at)) as avg_tat_seconds'),
                DB::raw('MIN(TIMESTAMPDIFF(SECOND, created_at, updated_at)) as min_tat_seconds'),
                DB::raw('MAX(TIMESTAMPDIFF(SECOND, created_at, updated_at)) as max_tat_seconds'),
                DB::raw('COUNT(id) as count')
            )
            ->whereNotNull('service')
            ->groupBy('service')
            ->get();

        $tatByFacility->each(function ($item) {
            $item->avg_tat_formatted = $this->formatSeconds($item->avg_tat_seconds);
            $item->min_tat_formatted = $this->formatSeconds($item->min_tat_seconds);
            $item->max_tat_formatted = $this->formatSeconds($item->max_tat_seconds);
        });

        $tatByService->each(function ($item) {
            $item->avg_tat_formatted = $this->formatSeconds($item->avg_tat_seconds);
            $item->min_tat_formatted = $this->formatSeconds($item->min_tat_seconds);
            $item->max_tat_formatted = $this->formatSeconds($item->max_tat_seconds);
        });

        return view('reports.turnaround-time', compact(
            'tatByFacility',
            'tatByService',
            'fromDate',
            'toDate'
        ));
    }

    private function formatSeconds(?int $seconds): string
    {
        if (is_null($seconds)) {
            return 'N/A';
        }

        $days = floor($seconds / 86400);
        $hours = floor(($seconds % 86400) / 3600);
        $minutes = floor(($seconds % 3600) / 60);

        $parts = [];
        if ($days > 0) {
            $parts[] = $days . 'd';
        }
        if ($hours > 0) {
            $parts[] = $hours . 'h';
        }
        if ($minutes > 0) {
            $parts[] = $minutes . 'm';
        }

        return empty($parts) ? '< 1m' : implode(' ', $parts);
    }

    public function referringFacilityReport(Request $request)
    {
        $fromDate = $request->input('from_date', Carbon::now()->subDays(30)->toDateString());
        $toDate = $request->input('to_date', Carbon::now()->toDateString());

        $referralsQuery = Referral::whereBetween('referrals.created_at', [$fromDate, $toDate])
            ->whereNotNull('referrals.referring_facility_id');

        $reportData = (clone $referralsQuery)
            ->join('m_f_l_s', 'referrals.referring_facility_id', '=', 'm_f_l_s.Code')
            ->select('m_f_l_s.Officialname', DB::raw('count(referrals.id) as count'))
            ->groupBy('m_f_l_s.Officialname')
            ->orderBy('count', 'desc')
            ->get();

        if ($request->has('export') && $request->get('export') === 'csv') {
            $fileName = 'referring_facility_report.csv';
            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];

            $columns = ['Referring Facility', 'Total Referrals Sent'];

            $callback = function() use($reportData, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);

                foreach ($reportData as $row) {
                    fputcsv($file, [$row->Officialname, $row->count]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return view('reports.referring-facility', compact(
            'reportData',
            'fromDate',
            'toDate'
        ));
    }

}
