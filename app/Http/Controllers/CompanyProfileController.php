<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\company_profile;
use DateTimeImmutable;
use Illuminate\Support\Facades\DB;

class CompanyProfileController extends Controller
{

    public function GetAll()
    {
        try {
            $com = company_profile::all();
        } catch (\Throwable $th) {
            return response()->json(array(
                'message' => 'Bad Request',
                'detail' => $th
            ), 400);
        }
        return $com;
    }

    public function GetByIdBool($id)
    {
        try {
            $com = DB::select('select * from company_profiles where company_profile_id = ' . $id);
        } catch (\Throwable $th) {
            return response()->json(array(
                'message' => 'Bad Request',
                'detail' => $th
            ), 400);
        }

        if (count($com) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function GetById($id)
    {
        try {
            $com = DB::select('select * from company_profiles where company_profile_id = ' . $id);
        } catch (\Throwable $th) {
            return response()->json(array(
                'message' => 'Bad Request',
                'detail' => $th
            ), 400);
        }

        if (count($com) > 0) {
            return response()->json($com[0], 200);
        } else {
            return response()->json(array(
                'message' => 'Company Not Found!'
            ), 400);
        }
    }

    public function Add(Request $request)
    {
        try {
            $com = new company_profile();
            $com->fill($request->all());
            $com->save();
            return $com;
        } catch (\Throwable $th) {
            return response()->json(array(
                'message' => 'Bad Request',
                'detail' => $th,
            ), 400);
        }
    }

    public function Update(Request $request, $id)
    {
        if (!$this->GetByIdBool($id)) {
            return response()->json(array(
                'message' => 'Company Profile of Id = ' . $id . ' is Not Found',
            ), 400);
        }

        try {
            $timestamp = time();
            DB::update('UPDATE company_profiles
            SET company_profile_name=\'' . $request->company_profile_name . '\', 
            company_profile_phone=\'' . $request->company_profile_phone . '\', 
            company_profile_email=\'' . $request->company_profile_email . '\', 
            company_profile_address=\'' . $request->company_profile_address . '\', 
            company_profile_image=\'' . $request->company_profile_image . '\', 
            company_profile_wifi=\'' . $request->company_profile_wifi . '\', 
            company_profile_set_up_point=\'' . $request->company_profile_set_up_point . '\',
            updated_at= NOW()
            WHERE company_profile_id = ' . $id);
            return $this->GetById($id);
        } catch (\Throwable $th) {
            return response()->json(array(
                'message' => 'Bad Request',
                'detail' => $th
            ), 400);
        }
    }

    public function Delete($id)
    {
        if (!$this->GetByIdBool($id)) {
            return response()->json(array(
                'message' => 'Company Profile of Id = ' . $id . ' is Not Found',
            ), 400);
        }

        try {
            DB::delete('delete from company_profiles where company_profile_id=' . $id);
            return response()->json(array(
                'message' => 'Company Profile of Id = ' . $id . ' is Deleted',
            ), 200);
        } catch (\Throwable $th) {
            return response()->json(array(
                'message' => 'Bad Request',
                'detail' => $th
            ), 400);
        }
    }

    public function ChangeImage(Request $request, $id)
    {
        if (!$this->GetByIdBool($id)) {
            return response()->json(array(
                'message' => 'Company Profile of Id = ' . $id . ' is Not Found',
            ), 400);
        }

        // Check if the name field is not null or empty.
        if (!isset($request->image) || $request->image === null || $request->image === '') {
            return response()->json(array(
                'message' => 'Bad Request',
            ), 400);
        }

        try {
            DB::update('update company_profiles set company_profile_image=\'' . $request->image . '\' where company_profile_id=' . $id);
            return response()->json(array(
                'message' => 'Company Profile Image Updated',
            ), 200);
        } catch (\Throwable $th) {
            return response()->json(array(
                'message' => 'Bad Request',
                'detail' => $th
            ), 400);
        }
    }

    public function ChangeName(Request $request, $id)
    {
        if (!$this->GetByIdBool($id)) {
            return response()->json(array(
                'message' => 'Company Profile of Id = ' . $id . ' is Not Found',
            ), 400);
        }

        if (!isset($request->name) || $request->name === null || $request->name === '') {
            return response()->json(array(
                'message' => 'Bad Request',
            ), 400);
        }

        try {
            DB::update('update company_profiles set company_profile_name=\'' . $request->name . '\' where company_profile_id=' . $id);
            return response()->json(array(
                'message' => 'Company Profile Name Updated',
            ), 200);
        } catch (\Throwable $th) {
            return response()->json(array(
                'message' => 'Bad Request',
                'detail' => $th
            ), 400);
        }
    }
}
