<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SearchController extends Controller
{
    public function index()
    {
        return view('welcome');
    }
    public function search(Request $request)
    {
        session(['results' => []]);
        try {

            $request->validate([
                'query' => 'required|array|min:1',
                'query.*' => 'required|string|max:500|min:3',
            ]);
            $queries = array_filter($request->input('query'));
            $allResults = [];
            foreach ($queries as $query) {

                $queryResult = array();
                $response = Http::get("https://api.valueserp.com/search", [
                    'api_key' => env('VALUE_SERP_API_KEY'),
                    'q' => $query,
                    'location' => 'India',
                    'gl' => 'in',
                    'hl' => 'en',
                    'google_domain' => 'google.co.in'
                ]);

                if ($response->successful()) {
                    $json = $response->json();
                    $result_status = $json['request_info']['success'] ?? false;
                    if (!$result_status) {
                        $fail_message = $json['request_info']['message'] ?? false;
                        $queryResult['message'] = $fail_message;
                        $queryResult['status'] = 'fail';
                        $queryResult['uid'] = uniqid();
                        $queryResult['query'] = $query;
                        $queryResult['no_of_result'] = 0;
                        array_push($allResults, $queryResult);
                        continue;
                    }
                    $results = $json['organic_results'] ?? [];
                    $no_of_results = count($results);
                    if ($no_of_results == 0) {
                        $queryResult['message'] = 'No results found';
                        $queryResult['status'] = 'fail';
                        $queryResult['uid'] = uniqid();
                        $queryResult['query'] = $query;
                        $queryResult['no_of_result'] = 0;
                        array_push($allResults, $queryResult);

                        continue;
                    }
                    $queryResult['status'] = 'success';
                    $queryResult['uid'] = uniqid();
                    $queryResult['message'] = 'Search results found';
                    $queryResult['query'] = $query;
                    $queryResult['no_of_result'] = $no_of_results;

                    foreach ($results as $result) {
                        $queryRes = [

                            'title' => $result['title'] ?? 'N/A',
                            'link' => $result['link'] ?? 'N/A',
                            'domain' => $result['domain'] ?? 'N/A',
                            'snippet' => $result['snippet'] ?? 'N/A',
                        ];
                        $queryResult['results'][] = $queryRes;
                    }
                    array_push($allResults, $queryResult);
                } else {
                    $queryResult['message'] = 'API request failed';
                    $queryResult['status'] = 'fail';
                    $queryResult['uid'] = uniqid();
                    $queryResult['query'] = $query;
                    $queryResult['no_of_result'] = 0;
                    array_push($allResults, $queryResult);
                }
            }
            session(['results' => $allResults]);

            return view('results', compact('allResults'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Invalid search query.']);
        }
    }
    public function export_csv(Request $request, $uid)
    {
        $results = session('results');
        $result = collect($results)
            ->filter(fn($item) => $item['uid'] == $uid)
            ->values();
        if ($result) {
            $result = $result[0];
            $csvFileName = $result['query'] . '_results.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$csvFileName\"",
            ];
            return response()->stream(function () use ($result) {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, ['#', 'Title', 'URL', 'Domain', 'Snippet']);
                $i = 1;
                foreach ($result['results'] as $res) {
                    fputcsv($handle, [$i++, $res['title'], $res['link'], $res['domain'], $res['snippet']]);
                }

                fclose($handle);
            }, 200, $headers);
        }
    }
    public function export_all_csv(Request $request)
    {
        $results = session('results');
        if ($results && !empty($results)) {


        $csvFileName =   'aggregated_search_results.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$csvFileName\"",
        ];

        return response()->stream(function () use ($results) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['#', 'Query', 'Title', 'URL', 'Domain', 'Snippet']);
            $i = 1;
            foreach ($results as $result) {

                if ($result['status'] == 'success') {


                    foreach ($result['results'] as $res) {
                        fputcsv($handle, [$i++, $result['query'], $res['title'], $res['link'], $res['domain'], $res['snippet']]);
                    }
                }
            }
            fclose($handle);
        }, 200, $headers);
    }else{
        return redirect()->back()->withErrors(['results' => 'No results found to export.']);
    }
    }
}
