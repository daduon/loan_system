<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;

use function Laravel\Prompts\error;

class BorrowScheduleController extends Controller
{
    public function updatePaidLoan($id, $brid, $seq)
    {
        try {
            if ($seq != 1) {
                $seqNo = $seq - 1;
                // dd($seqNo);
                $validatePaid = DB::select("
                SELECT
                CASE
                    WHEN  B.SCHEDULESTATUSCODE =0 THEN 'N'
                    ELSE 'Y'
                END AS isenable
                FROM BORROW_MASTERS AS A
                INNER JOIN BORROW_SCHEDULES AS B ON A.ID = B.BORROWING_ID
                WHERE A.ID = '$brid'
                AND B.SCHEDULESEQNO =$seqNo
            ");
                // dd($validatePaid);
                foreach ($validatePaid as $key) {
                    if ($key->isenable === 'N') {
                        // dd($key->isenable);
                        return response()->json(array(
                            'message' => 'N'
                        ), 200);
                    }
                };
            }

            DB::update('UPDATE borrow_schedules SET "schedulestatuscode" = 1 WHERE id =\'' . $id . '\'');


            $check = DB::select("
            SELECT
                    CASE
                        WHEN B.SCHEDULESTATUSCODE = '1' THEN 'Y'
                        ELSE 'N'
                    END AS  isPaid
            FROM BORROW_MASTERS A
                INNER JOIN BORROW_SCHEDULES B ON A.ID = B.BORROWING_ID
                    WHERE A.ID ='$brid'
            ");

            foreach ($check as $key) {
                if ($key->ispaid === 'N') {

                    return response()->json(array(
                        'message' => 'Success',
                        'borrowScheduleData' => $this->getBorrowScheduleData($id, $brid, $seq),
                    ), 200);
                }
            };

            DB::update('UPDATE BORROW_MASTERS SET "ispaid" = 1 WHERE id =\'' . $brid . '\'');

            return response()->json(array(
                'message' => 'Success',
                'borrowScheduleData' => $this->getBorrowScheduleData($id, $brid, $seq),
            ), 200);
        } catch (\Throwable $th) {
            return response()->json(array(
                'message' => 'Bad Request',
                'detail' => $th
            ), 400);
        }
    }

    public function updateUnpaidLoan($id)
    {
        try {
            DB::update('UPDATE borrow_schedules SET "schedulestatuscode" = 0 WHERE id =\'' . $id . '\'');
            return response()->json(array(
                'message' => 'Success'
            ), 200);
        } catch (\Throwable $th) {
            return response()->json(array(
                'message' => 'Bad Request',
                'detail' => $th
            ), 400);
        }
    }

    private function getBorrowScheduleData($id, $brid, $seq)
    {
        $results = DB::selectOne("
        SELECT
               B.COEMPLOYEE_ID                                         AS coemployeeid
             , C.REPAYPRINCIPAL 								       AS repayprincipal
             , B.CURRENCYCODE                                          AS currencyCode
         FROM BORROW_MASTERS AS B
        INNER JOIN BORROW_SCHEDULES AS C ON B.ID = C.BORROWING_ID
        WHERE B.ID = '$brid'
          AND C.ID = '$id'
          AND C.SCHEDULESEQNO = '$seq'");

        return $results;
    }
}
