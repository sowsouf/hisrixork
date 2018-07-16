<?php
/**
 * File app/Helpers.php
 *
 * Ce fichier contient des fonctions globales
 * utiles pour le fonctionnement du projet
 *
 * @author sofianeakbly
 */

namespace App;

use App\Models\Category;
use App\Models\Wcalendar;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * Class Helpers
 *
 * @package App
 */
class Helpers
{


    public static $days = [
        'week' => [],
        'we'   => [],
        'out'  => []
    ];

    public function __construct()
    {

    }

    public static function isActive($date = null)
    {
        $user = Auth::user();
        $wc = Wcalendar::where('id_user', '=', $user->id)
            ->where('start', '=', Carbon::parse($date)->toDateString())
            ->orWhere('stop', '=', Carbon::parse($date)->toDateString())->first();
        $color = null;

        if ($wc !== null && ($type = Category::where('id', $wc->id_category)->first()) !== null)
            $color = $type->name;
        if (in_array(Carbon::parse($date)->toDateString(), session()->get('out')))
            $color = 'out';

        $half = $wc !== null && $wc->half === 1 ? 'half' : '';

        return $wc !== null || Carbon::now()->toDateString() === Carbon::parse($date)->toDateString() || $color ? 'active ' . ("$color $half " ?? '') : '';
    }

    public static function getCat($date = null)
    {
        $user = Auth::user();
        if ($date !== null &&

            ($wc = Wcalendar::where('id_user', '=', $user->id)
                ->where('start', '=', Carbon::parse($date)->toDateString())
                ->orWhere('stop', '=', Carbon::parse($date)->toDateString())->first()) !== null && ($type = Category::where('id', $wc->id_category)->first()) !== null)
            $cat = $type->name;
        else
            $cat = null;

        return $cat;
    }

    public static function getColorByCategory($category)
    {
        $categories = array(
            'F'  => 'dc3545',
            'CP' => '28a745',
            'R'  => 'ffc107',
            'AM' => '00695c',
            'CS' => 'ef6c00',
            'TT' => 'aa66cc',
        );

        return $categories[$category] ?? '';
    }

    public static function getNumberRange($min, $max)
    {
        $arr = [];

        for ($i = $min; $i <= $max; $i++)
            $arr[] = $i;

        return $arr;
    }

}