<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Poll extends Controller
{
	function getPollList(Request $request)
	{
		if (!DB::table('t_Poll')->get()->isEmpty()) {
			return DB::table('t_Poll')->get();
		} else {
			return response('Polls not found', 404);
		}
	}
	
	function getPoll($id)
	{
		$poll = DB::table('t_Poll')->find($id);
		
		if ($poll) {
			return $poll;
		} else {
			return response('Poll with ID: "' . $id . '" not found', 404);
		}
	}
	
	function createPoll(Request $request)
	{
		$dbColumns = \Schema::getColumnListing('t_Poll');
		unset($dbColumns[0]);
		
		if (!array_diff($dbColumns, array_keys($request->all())) && !array_diff(array_keys($request->all()), $dbColumns)) {
			$insertedID = DB::table('t_Poll')->insertGetId($request->all());
			
			return response('Poll was successfully inserted. ID: "' . $insertedID . '"', 201);
		} else {
			return response('Not accepted. Not correct values are given.', 406);
		}
	}
	
	function updatePoll(Request $request, $id)
	{
		$poll = DB::table('t_Poll')->find($id);
		
		$dbColumns = \Schema::getColumnListing('t_Poll');
		unset($dbColumns[0]);
		
		if ($poll) {
			if (!array_diff(array_keys($request->all()), $dbColumns) && !empty($request->all())) {
				DB::table('t_Poll')->where('ID', $id)->update($request->all());
				
				return response('Poll was successfully updated. ID: "' . $id . '"', 200);
			} else {
				return response('Not accepted. Not correct values are given.', 406);
			}
		} else {
			return response('Poll with ID: "' . $id . '" not found', 404);
		}
	}
	
	function deletePoll($id)
	{
		if($id) {
			$poll = DB::table('t_Poll')->where('ID', $id)->get();
			
			if (!$poll->isEmpty()) {
				DB::table('t_Poll')->where('ID', $id)->delete();
				
				return response('Poll with ID: "' . $id . '" was deleted successfully', 200);
			} else {
				return response('Poll with ID: "' . $id . '" not found', 404);
			}
		} else {
			return response('Poll id is not specified', 404);
		}
	}
}