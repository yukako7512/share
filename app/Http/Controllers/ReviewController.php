<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Review;
use App\Event;
use App\UserEvent;
use App\User;

class ReviewController extends Controller
{
    public function create($id) {
        $reviews = new Review;
        return view('review.review', ['reviews' => $reviews,
                                      'id' => $id]) ;
    }
    
    public function reviewdone(Request $request, $id) {
        
        $reviews = new Review;
        $reviews->rating = $request->rating;
        $reviews->comment = $request->comment;
        $reviews->event_id = $id;
        $user = \Auth::user();
        $reviews->user_id = $user->id;
        $reviews->save();
        
        $user_event = UserEvent::where('user_id', $user->id)->where('event_id',$id);
        $user_event->update(['relationship'=>'done']);
        
        return view ('review.reviewdone', ['user' => $user]);
    }
        
        public function review_history($id) {
        $user = User::find($id);
        $events = $user->events;
        $reviewers = $events->users_through_reviews()->name;
        
        $my_reviews = $user->reviews_through_events;
        return view ('review.review_history', ['my_reviews' => $my_reviews,
                                                'reviewer' => $reviewers]);
    }
}