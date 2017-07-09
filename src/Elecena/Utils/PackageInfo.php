<?php

namespace Elecena\Utils;

/**
 * Parameters parser
 */
class PackageInfo {

	/**
	 * @param string $desc
	 * @return string|false
	 */
	public static function getPackage($desc) {
		$package = false;
		$desc = strtoupper($desc);

		/**
		 * "Swap" package signatures
		 *
		 * 64-LQFP -> LQFP64
		 */
		$desc = preg_replace('#(\d{2,})-([A-Z]{2,})#', '$2$1', $desc);

		$groups = [
			'TO-?(111|114|116|126|18|220|254|257|3|39|46|5|53|59|60|61|63|66|72|78|8|82|92)(SG|F)?',
			'TOP-?(3)',
			// https://en.wikipedia.org/wiki/Small_Outline_Integrated_Circuit
			'LQFP-?(100|128|144|176|208|32|44|48|52|64|80)',
			'DIL-?(8|14|16|18|20|22|24|28|32|36|40|42|48|64)',
			// https://en.wikipedia.org/wiki/Small_Outline_Integrated_Circuit
			'([MTS]?SOP?|TSSOP?|SOIC)-?(8|10|14|16|18|20|24|28|32|38)(\-W)?',
			'[CEMP]?DIP-?(8|14|16|18|20|22|24|28|32|36|40|42|48|64)',
			'[TUVWX]?DFN-?(3|4|6|8|10|12|14|16|20|22)',
			'HC-?49(-?[US])?',
			// http://www.topline.tv/DO.html / https://en.wikipedia.org/wiki/DO-204
			'DO-?(7|14|15|16|26|29|34|35|41|201AC|201AD|201AE|204-?AA|204-?AH|204-?AL)',
			// http://www.topline.tv/DO.html
			'SOD-?(27|57|61|64|66|68|80|81|83|87|88|89|91|107|118|119|121|125)',
			// https://en.wikipedia.org/wiki/Quad_Flat_No-leads_package / http://anysilicon.com/ultimate-guide-qfn-package/
			'(HTQFP|TQFP|LQFP|QFN|MLF|MLPD|MLPM|MPLPQ|VQFN|DFN)-?(10|14|16|20|24|28|32|38|40|44|48|52|56|64|68|80|100|112|120|128|144|176|208)',
			// https://en.wikipedia.org/wiki/Small-outline_transistor
			'SOT23-?(3|5|6|8)',
			// The SOT-227, or sometimes referred to as the ISOTOP® package
			'(SOT-?227(B|-4)?)|ISOTOP®',
			// http://www.ferret.com.au/c/richardson-electronics/100v-mosfet-modules-in-sp3-sp4-sp6-packages-n679793 / mosfet modules
			'SP(1|3|4|6|6-P)',
			// Clipwatt
			'CLIPWATT(-|\s)?(11|15|19)',
			// Ball Grid Array / https://en.wikipedia.org/wiki/Ball_grid_array
			'(BGA|CABGA|CSPBGA|DSBGA|FBGA|FCBGA|FCPBGA|FPBGA|FTBGA|HBGA|PBGA|TBGA|TFBGA|TWBGA|UBGA|VFBGA)-?(4|5|6|48|63|64|90|96|108|113|119|121|132|144|165|191|208|256|324|400|480|484|672|676|780|783|896|900|1152|1156|1517|1704|1760|1932)',

			// for normalization
			'(SOT-?186|SC-?67)',
		];
		$pattern = '#(^|-|,|:|\s|$|\[|\()(' . join('|', $groups) . ')(\)|\]|;|,|=|\s|/|$)#';

		if (preg_match(
			$pattern,
			$desc,
			$matches)
		) {
			$package = $matches[2];

			$package = str_replace(' ', '', $package); # remove spaces in 'CLIPWATT 19' => 'CLIPWATT19'
			$package = preg_replace('#([A-Z])-(\d+)#', '$1$2', $package); # remove dash in DIL-14 -> "DIL14"
			$package = preg_replace('#(TO|DO|CLIPWATT)(\d+)#', '$1-$2', $package); # add dash to TO92, DO-14 and CLIPWATT19 -> "TO-92"

			/**
			 * Normalize packages
			 *
			 * The DO-7 (also known as DO-204-AA)
			 * The DO-35 (also known as DO-204-AH or SOD27)
			 * The DO-41 (also known as DO-204-AL or SOD66)
			 */
			# var_dump($package);

			$normalizations = [
				'DO-204AA' => 'DO-7',
				'DO-204AH' => 'DO-35',
				'SOD27' => 'DO-35',
				'DO-204AL' => 'DO-41',
				'SOD66' => 'DO-41',

				// https://en.wikipedia.org/wiki/Small-outline_transistor
				'SOT323' => 'SOT23-3',
				'SOT416' => 'SOT23-3',
				'SOT353' => 'SOT23-5',
				'SOT363' => 'SOT23-6',
				'SOT28' => 'SOT23-8',

				// TO-220F also known as the SOT186 and SC67 is TO-220 like package, where the heatsink mounting tab has been encased in the plastic
				'SOT186' => 'TO-220F',
				'SC67' => 'TO-220F',

				// The SOT-227, or sometimes referred to as the ISOTOP® package
				'ISOTOP®' => 'SOT-227',
				'SOT227' => 'SOT-227',
				'SOT227-4' => 'SOT-227',
				'SOT227B' => 'SOT-227B',
			];

			if (array_key_exists($package, $normalizations)) {
				$package = $normalizations[$package];
			}
		}
		return $package;
	}
}
