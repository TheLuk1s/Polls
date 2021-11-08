<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Option extends Controller
{
	function getOptionList($pollID)
	{
		if (!DB::table('t_Options')->where('IDPoll', $pollID)->get()->isEmpty()) {
			return DB::table('t_Options')->where('IDPoll', $pollID)->get();
		} else {
			return response('Options not found', 404);
		}
	}
	
	function getOption($pollID, $optionID)
	{
		$option = DB::table('t_Options')->where([['IDPoll', $pollID], ['ID', $optionID]])->get();
		
		if (!$option->isEmpty()) {
			return $option;
		} else {
			return response('Option with ID: "' . $optionID . '" not found', 404);
		}
	}
	
	function createOption(Request $request, $pollID)
	{
		$dbColumns = \Schema::getColumnListing('t_Options');
		unset($dbColumns[0]);
		
		$requestValues = $request->all();
		$requestValues['IDPoll'] = $pollID;
		
		$poll = DB::table('t_Poll')->where('ID', $pollID)->get();
		
		if(!$poll->isEmpty()) {
			if (!array_diff($dbColumns, array_keys($requestValues)) && !array_diff(array_keys($requestValues), $dbColumns)) {
				$insertedID = DB::table('t_Options')->insertGetId($requestValues);
				
				return response('Option was successfully inserted. ID: "' . $insertedID . '"', 201);
			} else {
				return response('Not accepted. Not correct values are given.', 406);
			}
		} else {
			return response('Poll with ID: "' . $pollID . '" not found', 404);
		}
	}
	
	function updateOption(Request $request, $pollID, $optionID)
	{
		$dbColumns = \Schema::getColumnListing('t_Options');
		unset($dbColumns[0]);
		
		$requestValues = $request->all();
		$requestValues['IDPoll'] = $pollID;
		
		$poll = DB::table('t_Poll')->where('ID', $pollID)->get();
		$option = DB::table('t_Options')->where([['ID', $optionID], ['IDPoll', $pollID]])->get();
		
		if(!$poll->isEmpty()) {
			if(!$option->isEmpty()) {
				if (!array_diff(array_keys($requestValues), $dbColumns) && count($requestValues) > 1) {
					DB::table('t_Options')->where('ID', $optionID)->update($requestValues);
					
					return response('Option was successfully updated. ID: "' . $optionID . '"', 200);
				} else {
					return response('Not accepted. Not correct values are given.', 406);
				}
			} else {
				return response('Option with ID: "' . $optionID . '" for poll ID: "' . $pollID . '" not found', 404);
			}
		} else {
			return response('Poll with ID: "' . $pollID . '" not found', 404);
		}
	}
	
	function deleteOption($pollID, $optionID)
	{
		if($pollID && $optionID) {
			$option = DB::table('t_Options')->where([['IDPoll', $pollID], ['ID', $optionID]])->get();
			
			if (!$option->isEmpty()) {
				DB::table('t_Options')->where([['IDPoll', $pollID], ['ID', $optionID]])->delete();
				return response('Option with ID: "' . $optionID . '" was deleted successfully', 200);
			} else {
				return response('Option with ID: "' . $optionID . '" not found', 404);
			}
		} else {
			return response('Poll and / or option id is not specified', 404);
		}
	}
}