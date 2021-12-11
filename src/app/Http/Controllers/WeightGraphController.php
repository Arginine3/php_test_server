<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\WeightLog;

class WeightGraphController extends Controller
{
    /**
     * 1. 体重ログデータを取り出す
     * 2. Viewにログデータを渡す
     *
     */
    // function show(Request $request){
	// 	//体重ログデータを取り出す
	// 	//今年の体重データを取り出す
	// 	$log_list = WeightLog::where("date_key","like",date("Y") . "%")->get();

	// 	//Viewにログデータを渡す
	// 	return view("weight_graph",["log_list" => $log_list]);
	// }

        /**
     * X年Y月の平均、最大、最小を計算する関数
     *
     */
    function getWeightLogData($date_key){
        $sum = 0;
        $max = 0;
        $min = 500;
        $logs = WeightLog::where("date_key","like",$date_key . "%")->get();

        foreach($logs as $log){
            $weight = $log->weight;
            $sum += $weight;
            $max = max($max, $weight);
            $min = min($min, $weight);
        }

        $avg = ($logs->count() > 0) ? $sum / $logs->count() : 0;

        return [
            $avg,
            $max,
            $min
        ];
    }

    function show(Request $request){
        $avg_weight_log = [];
        $max_weight_log = [];
        $min_weight_log = [];

        //取り出す対象
        $target_days = [
            "202106",
            "202107",
            "202108",
            "202109",
            "202110",
            "202111",
        ];

        foreach($target_days as $date_key){
            list($avg, $max, $min) = $this->getWeightLogData($date_key);
            $avg_weight_log[] = $avg;
            $max_weight_log[] = $max;
            $min_weight_log[] = $min;
        }

        return view("weight_graph",[
            "label" => [
                "2021年6月",
                "2021年7月",
                "2021年8月",
                "2021年9月",
                "2021年10月",
                "2021年11月",
            ],
            "avg_weight_log" => $avg_weight_log,
            "max_weight_log" => $max_weight_log,
            "min_weight_log" => $min_weight_log,
        ]);
    }
}
