<?php

namespace App\Http\Controllers;

use App\Patient;
use Illuminate\Http\Request;
use JWTAuth;

class PatientController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index()
    {
        $response = $this->user->patients()->get([ 'name','id_number','department','tel','dob','location','illness','medication','payment','created_at','updated_at'])
        ->toArray();
        return response()->json([
            'success' => true,
            'user' => 'admin',
            'response' => $response
        ]);

    }
    public function reception()
    {
        $response = $this->user->patients()->get([ 'name','id_number','department','dob','created_at','updated_at'])
        ->toArray();
        return response()->json([
            'success' => true,
            'user' => 'reception',
            'response' => $response
        ]);

        
    }
    public function nurse()
    {
        $response = $this->user->patients()->get([ 'name','id_number','department','location','medication','payment','created_at','updated_at'])
        ->toArray();
        return response()->json([
            'success' => true,
            'user' => 'nurse',
            'response' => $response
        ]);
    }
    public function doctor()
    {
        $response = $this->user->patients()->get([ 'name','id_number','department','dob','location','illness','medication','created_at','updated_at'])
        ->toArray();
        return response()->json([
            'success' => true,
            'user' => 'admin',
            'response' => $response
        ]);
    }

    public function show($id)
    {
        $patient = $this->user->patients()->find($id);

        if (!$patient) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, patient with id ' . $id . ' cannot be found'
            ], 400);
        }

        return $patient;
    }

    public function store(Request $request)
    {
       
        $this->validate($request, [
           
            'name' => 'required|string',
            'id_number' => 'required|string|max:8|min:8|unique:patients',
            'tel' => 'required|string|min:10|max:10|unique:patients',
            'dob' => 'required|string',
            'location' => 'required|string',
            'department' => 'required|string',
        ]);

        $patient = new Patient();
        
        $patient->name = $request->name;
        $patient->id_number = $request->id_number;

        $patient->tel = $request->tel;
        $patient->dob = $request->dob;
        $patient->location = $request->location;
        $patient->department = $request->department;
        $patient->illness = $request->illness;
        $patient->medication = $request->medication;
        $patient->payment = $request->payment;

        if ($this->user->patients()->save($patient))
            return response()->json([
                'success' => true,
                'patient' => $patient
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Sorry, patient could not be added'
            ], 500);
    }

    public function update(Request $request, $id)
    {
        $patient = $this->user->patients()->find($id);

        if (!$patient) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, patient with id ' . $id . ' cannot be found'
            ], 400);
        }

        $updated = $patient->fill($request->all())
            ->save();

        if ($updated) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, patient could not be updated'
            ], 500);
        }
    }

    public function destroy($id)
    {
        $patient = $this->user->patients()->find($id);

        if (!$patient) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, patient with id ' . $id . ' cannot be found'
            ], 400);
        }

        if ($patient->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Patient could not be deleted'
            ], 500);
        }
    }
}