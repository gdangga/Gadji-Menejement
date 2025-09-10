<?php
namespace App\View\Components\Layouts;
use Illuminate\View\Component;
class App extends Component {
    public $header;
    public function __construct($header = null) {
        $this->header = $header;
    }
    public function render() {
        return view('layouts.app');
    }
}