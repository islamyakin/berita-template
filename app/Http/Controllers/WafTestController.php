<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * A form for watching the edge WAF work.
 *
 * The point of the page is what NEVER reaches it: a request carrying an
 * injection payload is answered 403 by the proxy, so the handler below is not
 * called at all and the browser shows the WAF's own page. Anything that does
 * arrive here is a request the rules judged ordinary, and the page echoes it
 * back so the difference is visible rather than assumed.
 *
 * The echo is Blade's, so it is escaped on the way out: this tests the WAF, it
 * is not a hole for the WAF to cover. A payload that gets through is a rule
 * worth tuning, not a script that runs.
 */
class WafTestController extends Controller
{
    public function index(): View
    {
        return view('uji-waf', ['submitted' => null, 'method' => null]);
    }

    public function store(Request $request): View
    {
        return view('uji-waf', [
            'submitted' => (string) $request->input('payload', ''),
            'method' => $request->method(),
        ]);
    }
}
