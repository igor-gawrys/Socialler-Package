<?php

namespace Igorgawrys\Socialler\Http\Controllers;

use Illuminate\Http\Request;

class StudentsController extends Controller
{
    
	public function Index() {
	
       return view('Socialler::static.index');
    	
	}
    
}
