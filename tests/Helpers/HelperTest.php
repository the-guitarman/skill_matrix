<?php

namespace Tests\Helpers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Helpers\Helper;
use Carbon\Carbon;

class HelperTest extends TestCase
{
    public function testLocalize()
    {
        $valueToParse = '20180621113330';
        $this->runLocalizeTests($valueToParse);
        $this->runLocalizeTests(['date' => $valueToParse]);
        $valueToParse = Carbon::parse($valueToParse);
        $this->runLocalizeTests($valueToParse);
    }

    protected function runLocalizeTests($valueToParse)
    {
        $this->assertEquals("21.06.2018", Helper::localize($valueToParse, 'date.formats.date.long'));
        $this->assertEquals("21.06.18", Helper::localize($valueToParse, 'date.formats.date.short'));
        $this->assertEquals("21.06.2018 11:33:30", Helper::localize($valueToParse));
        $this->assertEquals("21.06.18 11:33", Helper::localize($valueToParse, 'date.formats.datetime.short'));
    }
}
