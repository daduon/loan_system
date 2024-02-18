<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SummaryController extends Controller
{
    public function GetSummaryByCusId($id)
    {
        try {
            $com = DB::select('SELECT * ,(C.repayinterest::DECIMAL + C.repayprincipal::DECIMAL + C.taxamount::DECIMAL) as totalpay FROM customers AS A
            INNER JOIN BORROW_MASTERS AS B ON B.customer_id = A.id
            INNER JOIN BORROW_SCHEDULES AS C ON C.borrowing_id = B.id Where A.id = \'' . $id . '\' ORDER BY C.id');
            return response()->json($com);
        } catch (\Throwable $th) {
            return response()->json(array(
                'message' => 'Bad Request',
                'detail' => $th
            ), 400);
        }
    }

    public function GetSummaryByCusIdByBorrowId($id, $bid)
    {
        try {
            $com = DB::select('SELECT * ,(C.repayinterest::DECIMAL + C.repayprincipal::DECIMAL + C.taxamount::DECIMAL) as totalpay
            FROM customers AS A
            INNER JOIN BORROW_MASTERS AS B ON B.customer_id = A.id
            INNER JOIN BORROW_SCHEDULES AS C ON C.borrowing_id = B.id Where A.id = \'' . $id . '\' And B.id = \'' . $bid . '\'  ORDER BY C.id');
            $com2 = DB::select('SELECT SUM(C.repayinterest::DECIMAL) as totalipay, SUM(C.repayprincipal::DECIMAL) as totalppay, SUM(C.taxamount::DECIMAL) as totaltpay
            ,(SUM(C.repayinterest::DECIMAL) + SUM(C.repayprincipal::DECIMAL) + SUM(C.taxamount::DECIMAL)) as grand_total
            FROM customers AS A
            INNER JOIN BORROW_MASTERS AS B ON B.customer_id = A.id
            INNER JOIN BORROW_SCHEDULES AS C ON C.borrowing_id = B.id Where A.id = \'' . $id . '\' And B.id = \'' . $bid . '\'  Group By A.id');
            return response()->json(array(
                'detail' => $com,
                'summary' => $com2
            ));
        } catch (\Throwable $th) {
            return response()->json(array(
                'message' => 'Bad Request',
                'detail' => $th
            ), 400);
        }
    }

    public function GetSummaryPrintByCusIdByBorrowId($id, $bid)
    {
        try {
            $com = DB::select('SELECT A.*
                                    , B.*
                                    , C.NAME
                                    , C.PHONE
                                    , C.ADDRESS
                                 FROM customers AS A
                                INNER JOIN BORROW_MASTERS AS B
                                   ON B.customer_id = A.id
                           INNER JOIN C_O_EMPLOYEES C
                                   ON B.COEMPLOYEE_ID = C.ID
                                WHERE A.id = \'' . $id . '\'
                                  AND B.id = \'' . $bid . '\'');
            $sql2 = DB::select('SELECT *, (C.repayinterest::DECIMAL + C.repayprincipal::DECIMAL + C.taxamount::DECIMAL) as totalpay FROM borrow_schedules AS C WHERE borrowing_id = \'' . $bid . '\' ORDER BY id');
            return response()->json(array(
                'header' => $com[0],
                'schedules' => $sql2
            ));
        } catch (\Throwable $th) {
            return response()->json(array(
                'message' => 'Bad Request',
                'detail' => $th
            ), 400);
        }
    }

    public function GetPaidSummaryByCusId($id)
    {
        try {
            $com = DB::select('SELECT * ,(C.repayinterest::DECIMAL + C.repayprincipal::DECIMAL + C.taxamount::DECIMAL) as totalpay FROM customers AS A
            INNER JOIN BORROW_MASTERS AS B ON B.customer_id = A.id
            INNER JOIN BORROW_SCHEDULES AS C ON C.borrowing_id = B.id Where A.id = \'' . $id . '\' And C.schedulestatuscode = 1 ORDER BY C.id');
            return response()->json($com);
        } catch (\Throwable $th) {
            return response()->json(array(
                'message' => 'Bad Request',
                'detail' => $th
            ), 400);
        }
    }

    public function GetPaidSummaryByCusIdByBorrowId($id, $bid)
    {
        try {
            $com = DB::select('SELECT * ,(C.repayinterest::DECIMAL + C.repayprincipal::DECIMAL + C.taxamount::DECIMAL) as totalpay FROM customers AS A
            INNER JOIN BORROW_MASTERS AS B ON B.customer_id = A.id
            INNER JOIN BORROW_SCHEDULES AS C ON C.borrowing_id = B.id Where A.id = \'' . $id . '\' And C.schedulestatuscode = 1 And B.id = \'' . $bid . '\'  ORDER BY C.id');
            $com2 = DB::select('SELECT SUM(C.repayinterest::DECIMAL) as totalipay, SUM(C.repayprincipal::DECIMAL) as totalppay, SUM(C.taxamount::DECIMAL) as totaltpay
            ,(SUM(C.repayinterest::DECIMAL) + SUM(C.repayprincipal::DECIMAL) + SUM(C.taxamount::DECIMAL)) as grand_total
            FROM customers AS A
            INNER JOIN BORROW_MASTERS AS B ON B.customer_id = A.id
            INNER JOIN BORROW_SCHEDULES AS C ON C.borrowing_id = B.id Where A.id = \'' . $id . '\' And C.schedulestatuscode = 1 And B.id = \'' . $bid . '\'  Group By A.id');
            return response()->json(array(
                'detail' => $com,
                'summary' => $com2
            ));
        } catch (\Throwable $th) {
            return response()->json(array(
                'message' => 'Bad Request',
                'detail' => $th
            ), 400);
        }
    }

    public function GetUnpaidSummaryByCusIdByBorrowId($id, $bid)
    {
        try {
            $com = DB::select('SELECT * , (C.repayinterest::DECIMAL + C.repayprincipal::DECIMAL + C.taxamount::DECIMAL) as totalpay FROM customers AS A
            INNER JOIN BORROW_MASTERS AS B ON B.customer_id = A.id
            INNER JOIN BORROW_SCHEDULES AS C ON C.borrowing_id = B.id Where A.id = \'' . $id . '\' And C.schedulestatuscode = 0 And B.id = \'' . $bid . '\' ORDER BY C.id');
            $com2 = DB::select('SELECT SUM(C.repayinterest::DECIMAL) as totalipay, SUM(C.repayprincipal::DECIMAL) as totalppay, SUM(C.taxamount::DECIMAL) as totaltpay
            ,(SUM(C.repayinterest::DECIMAL) + SUM(C.repayprincipal::DECIMAL) + SUM(C.taxamount::DECIMAL)) as grand_total
            FROM customers AS A
            INNER JOIN BORROW_MASTERS AS B ON B.customer_id = A.id
            INNER JOIN BORROW_SCHEDULES AS C ON C.borrowing_id = B.id Where A.id = \'' . $id . '\' And C.schedulestatuscode = 0 And B.id = \'' . $bid . '\' Group By A.id');
            return response()->json(array(
                'detail' => $com,
                'summary' => $com2
            ));
        } catch (\Throwable $th) {
            return response()->json(array(
                'message' => 'Bad Request',
                'detail' => $th
            ), 400);
        }
    }

    public function GetUnpaidSummaryByCusId($id)
    {
        try {
            $com = DB::select('SELECT * FROM customers AS A
            INNER JOIN BORROW_MASTERS AS B ON B.customer_id = A.id
            INNER JOIN BORROW_SCHEDULES AS C ON C.borrowing_id = B.id Where A.id = \'' . $id . '\' And C.schedulestatuscode = 0 ORDER BY C.id');
            return response()->json($com);
        } catch (\Throwable $th) {
            return response()->json(array(
                'message' => 'Bad Request',
                'detail' => $th
            ), 400);
        }
    }

    public function GetBorrowIdByCusId($id)
    {
        try {
            $com = DB::select('SELECT B.id FROM customers AS A
            INNER JOIN BORROW_MASTERS AS B ON B.customer_id = A.id Where A.id = \'' . $id . '\' ORDER BY B.id DESC');
            return response()->json($com);
        } catch (\Throwable $th) {
            return response()->json(array(
                'message' => 'Bad Request',
                'detail' => $th
            ), 400);
        }
    }
}
