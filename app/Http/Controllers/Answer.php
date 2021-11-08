<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Answer extends Controller
{
	function getAnswerList(Request $request, $pollID)
	{
		if (!DB::table('t_Answers')->where('IDPoll', $pollID)->get()->isEmpty()) {
			return DB::table('t_Answers')->where('IDPoll', $pollID)->get();
		} else {
			return response('Answers not found', 404);
		}
	}
	
	function getAnswer($pollID, $answerID)
	{
		$answer = DB::table('t_Answers')->where([['IDPoll', $pollID], ['ID', $answerID]])->get();
		
		if (!$answer->isEmpty()) {
			return $answer;
		} else {
			return response('Answer with ID: "' . $answerID . '" not found', 404);
		}
	}
	
	function createAnswer(Request $request, $pollID)
	{
		$dbColumns = \Schema::getColumnListing('t_Answers');
		unset($dbColumns[0]);
		
		$requestValues = $request->all();
		$requestValues['IDPoll'] = $pollID;
		
		$poll = DB::table('t_Poll')->where('ID', $pollID)->get();
		
		if(!$poll->isEmpty()) {
			if (!array_diff($dbColumns, array_keys($requestValues)) && !array_diff(array_keys($requestValues), $dbColumns)) {
				$insertedID = DB::table('t_Answers')->insertGetId($requestValues);
				
				return response('Answer was successfully inserted. ID: "' . $insertedID . '"', 201);
			} else {
				return response('Not accepted. Not correct values are given.', 406);
			}
		} else {
			return response('Poll with ID: "' . $pollID . '" not found', 404);
		}
	}
	
	function updateAnswer(Request $request, $pollID, $answerID)
	{
		$dbColumns = \Schema::getColumnListing('t_Answers');
		unset($dbColumns[0]);
		
		$requestValues = $request->all();
		$requestValues['IDPoll'] = $pollID;
		
		$poll = DB::table('t_Poll')->where('ID', $pollID)->get();
		$answer = DB::table('t_Answers')->where([['ID', $answerID], ['IDPoll', $pollID]])->get();
		
		if(!$poll->isEmpty()) {
			if(!$answer->isEmpty()) {
				if (!array_diff(array_keys($requestValues), $dbColumns) && count($requestValues) > 1) {
					DB::table('t_Answers')->where('ID', $answerID)->update($requestValues);
					
					return response('Answer was successfully updated. ID: "' . $answerID . '"', 200);
				} else {
					return response('Not accepted. Not correct values are given.', 406);
				}
			} else {
				return response('Answer with ID: "' . $answerID . '" for poll ID: "' . $pollID . '" not found', 404);
			}
		} else {
			return response('Poll with ID: "' . $pollID . '" not found', 404);
		}
	}
	
	function deleteAnswer($pollID, $answerID)
	{
		if($pollID && $answerID) {
			$answer = DB::table('t_Answers')->where([['IDPoll', $pollID], ['ID', $answerID]])->get();
			
			if (!$answer->isEmpty()) {
				DB::table('t_Answers')->where([['IDPoll', $pollID], ['ID', $answerID]])->delete();
				return response('Answer with ID: "' . $answerID . '" was deleted successfully', 200);
			} else {
				return response('Answer with ID: "' . $answerID . '" not found', 404);
			}
		} else {
			return response('Poll and / or answer id is not specified', 404);
		}
	}
}