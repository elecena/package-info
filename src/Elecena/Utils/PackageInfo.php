<?php

namespace Elecena\Utils;

/**
 * IC packages parsing utilities
 *
 * @see http://www.intersil.com/en/support/packaginginfo.html
 * @see http://www.interfacebus.com/semiconductor-transistor-packages.html
 * @see https://www.torexsemi.com/technical-support/packages/
 * @see http://www.marsport.org.uk/smd/package%20equivalents.htm
 */
class PackageInfo {

	/**
	 * @param string $desc
	 * @return string|false
	 */
	public static function getPackage($desc) {
		$package = false;
		$desc = mb_strtoupper($desc);

		// remove noise
		$desc = strtr($desc, '®', ' ');

		// "swap" package signatures / 64-LQFP -> LQFP64
		$desc = preg_replace('#(\d{1,})-([2A-Z]{2,})#', '$2$1', $desc);

		// DIP 6 -> DIP-6 / but do not touch "MMU 0102"
		$desc = preg_replace('#(\b[A-Z]{2,})\s([1-9]\d?)#', '$1-$2', $desc);

		$groups = [
			'TO-?(111|114|116|126|18|253|254|257|262|262AA|268|3|39|46|5|53|59|60|61|63|66|72|78|8|82)',
			// https://en.wikipedia.org/wiki/TO-92
			'TO-?92(-3)?',
			// https://en.wikipedia.org/wiki/TO-220
			'I?TO-?220(AB|AC|F|FP|SG|-3|-5)?',
			'TOP-?(3)',
			// If more heat needs to be dissipated, devices in the also widely used TO-247 (or TO-3P) package can be selected / TO-3PF variant a slightly lower one
			'TO-?(247(AC|AD|-3)?|3|3P|3PF)',
			// https://en.wikipedia.org/wiki/Small_Outline_Integrated_Circuit
			'(LF|L|M|P|V|VF)?QFP(N)?-?(100|128|144|176|208|32|44|48|52|64|80)',
			'DIL-?(8|14|16|18|20|22|24|28|32|36|40|42|48|64)',
			// https://en.wikipedia.org/wiki/Small_Outline_Integrated_Circuit
			'((H|HT|HTS|LS|M|S|T|TS|TV|Q|V|VS)?SOP?|SOIC)-?(4|5|6|8|10|12|14|16|18|20|24|28|30|32|36|38|44|48|54|56)(-J|-W|-EP|-POWERPAD)?',
			// https://en.wikipedia.org/wiki/Dual_in-line_package
			'(CERDIP|CDIP|PDIP|DIP|MDIP|EDIP)-?(6|8|10|12|14|16|18|20|22|24|28|32|36|40|42|48|64)',
			// Slim plastic dip" (0.3" lead spacing) versus the usual 0.4" spacing used on 28- and 40-pin packages
			'SP?DIP-?(28|40)',
			'HC-?49(-?[US])?',
			// http://www.topline.tv/DO.html / https://en.wikipedia.org/wiki/DO-204 / https://en.wikipedia.org/wiki/DO-214 / https://en.wikipedia.org/wiki/Metal_electrode_leadless_face
			'DO-?(7|14|15|16|26|29|34|35|41|201AC|201AD|201AE|204-?AA|204-?AH|204-?AL|205AB|200AA|200AB|((213|214)(AA|AB|AC|BA)))',
			// http://www.topline.tv/DO.html
			'SOD-?(27|57|61|64|66|68|80|81|83|87|88|89|91|107|118|119|121|125)',
			// SOD323 / http://www.nxp.com/packages/SOD323
			'SOD-?(323|523)F?',
			'SC-?(90|59A)',
			'TO-?236AA',
			//  Quad Flat No-leads package / https://en.wikipedia.org/wiki/Quad_Flat_No-leads_package#Variants / http://anysilicon.com/ultimate-guide-qfn-package/
			'(CDFN|DFN|DQFN|DRMLF|LLP|LPCC|MLF|TMLF|MLPD|MLPM|MLPQ|QFN|QFN-TEP|TDFN|TQFN|UQFN|UTDFN|VQFN|WQFN|XDFN|DHVQFN|WDFN|UDFN)-?(4|5|6|8|10|12|14|16|20|24|28|32|38|40|44|48|52|56|64|68|80|100|112|120|128|144|176|208)-?(EP|S)?',
			// Quad Flat Package / https://en.wikipedia.org/wiki/Quad_Flat_Package
			'(BQFP|BQFPH|CQFP|EQFP|FQFP|LQFP|MQFP|NQFP|SQFP|TDFP|TQFP|VQFP|VTQFP|HTQFP)-?(4|5|6|8|10|14|16|20|24|28|32|38|40|44|48|52|56|64|68|80|100|112|120|128|144|176|208)',
			// https://en.wikipedia.org/wiki/Small-outline_transistor / Many manufacturers[1][2] also offer the nearly identical thin small outline transistor (TSOT)
			'T?SOT-?23-?(3|5|6|8)?',
			'SOT-?(323|353|363)-?(3)?',
			'SC-?70-?(3|4|5|6)?',
			'SOT-?(490|416FL)',
			'EMT3F',
			'SC-?89',
			'TSOT-?5',
			'SOT-?(143|343|563|666)',
			// The SOT-227, or sometimes referred to as the ISOTOP® package
			'(SOT-?227(B|-4)?)|ISOTOP',
			// https://en.wikipedia.org/wiki/Small-outline_transistor#SOT223_.28.3DSOT223-4.29 / http://www.nxp.com/packages/SOT223
			'SOT-?223-?(3|4|5|6|8)?',
			'(SC-?73)|(TO-?261AA)|(SM-?8)',
			// https://en.wikipedia.org/wiki/Small-outline_transistor#SOT89-3
			'SOT?-89-?(3|4|5)?',
			// http://www.ferret.com.au/c/richardson-electronics/100v-mosfet-modules-in-sp3-sp4-sp6-packages-n679793 / mosfet modules
			'SP(1|3|4|6|6-P)',
			// Clipwatt
			'CLIPWATT(-|\s)?(11|15|19)',
			// Ball Grid Array / https://en.wikipedia.org/wiki/Ball_grid_array
			'(BGA|CABGA|CSPBGA|DSBGA|FBGA|FCBGA|FCPBGA|FPBGA|FTBGA|HBGA|PBGA|TBGA|TFBGA|TWBGA|UBGA|VFBGA)-?(4|5|6|48|63|64|90|96|108|113|119|121|132|144|165|191|208|256|324|400|480|484|672|676|780|783|896|900|1152|1156|1517|1704|1760|1932)',
			// DPAK (TO-252) / https://en.wikipedia.org/wiki/TO-263
			'(D-?PAK|D2PAK|DDPAK)-?(3|5)?',
			'TO-?(252|252-3|252-5|263|263-5)(AA|AB)?',
			// SON
			'(PG-TD|W|V|X|X2)?SON-?(4|6|8|10)',
			// Leadframe Chip Scale Package / https://en.wikipedia.org/wiki/Chip-scale_package
			'(LFCSP|CSP|FCCSP|CSP|WL-CSP)-?(8|10|16|20|24|32|48|64)-?(VQ|WD|WQ)?',
			// Chip carrier / https://en.wikipedia.org/wiki/Chip_carrier
			'(BCC|CLCC|LCC|LCCC|DLCC|PLCC)-?(4|6|8|10|20|28|32|44|52|68|84)',
			// μMAX seems to be a package solely used by Maxim. It's an 8 pin SMT package, about as wide as an SO-8, but just 3mm long instead of the 5mm of an SO-8
			'(UMAX|USOP)-?(8|10)',
			// Torex packages / https://www.torexsemi.com/technical-support/packages/
			'USP(N|Q)?-?(3|4|6|10)(B|B03)?',
			// SOT-416 (or SOT-523 / SOT-75) / http://www.nxp.com/packages/SOT416.html
			'SOT-?(416|523)',
			'SC-?75A?',
			// MinSO [STMicroelectronics] / http://pl.mouser.com/Semiconductors/Amplifier-ICs/Analog-Comparators/_/N-cib1w?P=1z0xw9w&pop=1wwj
			'MINISO-?(8)',
			// Melf / https://en.wikipedia.org/wiki/Metal_electrode_leadless_face
			'MM(A|B|U)',
			'(MICRO|MINI)?(-|\s)?MELF',
			// (S)SOT packages / http://www.marsport.org.uk/smd/package%20equivalents.htm
			'S?SOT-?(24|25|26|457)',
			'SMQ|SM6|SMV|SC-?74',
			// TO-277 (Z3) / TO-277A (SMPC)
			'TO-?277A?',
			'Z3|SMPC',

			// for normalization
			'(SOT-?186|SC-?67)',
		];
		$pattern = '#(^|-|,|:|\s|$|\[|\(|/)(' . join('|', $groups) . ')(\)|\]|;|,|=|\s|/|$)#';

		if (preg_match(
			$pattern,
			$desc,
			$matches)
		) {
			$package = $matches[2];

			$package = str_replace(' ', '', $package); # remove spaces in 'CLIPWATT 19' => 'CLIPWATT19'
			$package = preg_replace('#([A-Z])-([A-Z\d]+)#', '$1$2', $package); # remove dash in DIL-14 -> "DIL14"
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

				// general purpose Zener diodes in a SOD323F (SC-90)
				'SC90' => 'SOD323F',

				// https://en.wikipedia.org/wiki/Small-outline_transistor
				'SOT23' => 'SOT23-3',
				'SOT323' => 'SOT23-3',
				'TO-236AA' => 'SOT23-3',
				'SC59A' => 'SOT23-3',
				'SOT353' => 'SOT23-5',
				'SOT363' => 'SOT23-6',
				'SOT28' => 'SOT23-8',
				'TSOT5' => 'TSOT23-5',
				'SC70-3' => 'SOT23-3',
				'SC70-5' => 'SOT23-5',
				'SC70-6' => 'SOT23-6',
				'SC89' => 'SOT490',
				'SOT416FL' => 'SOT490',
				'EMT3F' => 'SOT490',
				'TO-253' => 'SOT143',
				'SMQ' => 'SOT24',

				// TO-220F also known as the SOT186 and SC67 is TO-220 like package, where the heatsink mounting tab has been encased in the plastic
				'SOT186' => 'TO-220F',
				'SC67' => 'TO-220F',

				// The SOT-227, or sometimes referred to as the ISOTOP® package
				'ISOTOP' => 'SOT-227',
				'SOT227' => 'SOT-227',
				'SOT227-4' => 'SOT-227',
				'SOT227B' => 'SOT-227B',

				// https://en.wikipedia.org/wiki/Small-outline_transistor#SOT89-3
				'SOT89' => 'SOT89-3',
				'SOT89-4' => 'SOT89-3', // Some call this package a SOT89-4, since it visually appears to have four leads when looking down at the part.

				// https://en.wikipedia.org/wiki/Small-outline_transistor#SOT223_.28.3DSOT223-4.29
				'SC73' => 'SOT223',
				'TO-261AA' => 'SOT223',
				'SM8' => 'SOT223-8',

				// TO-252 is known as DPAK (Decawat Package)
				// Package can have 3 pins with 90 mils pitch, or 5 pins with 45 mils pitch.
				// @see https://en.wikipedia.org/wiki/TO-252
				'DPAK' => 'TO-252',
				'TO-252AA' => 'TO-252',

				// TO-263 is known as DDPAK
				// @see https://en.wikipedia.org/wiki/TO-263
				'DDPAK' => 'TO-263',
				'DDPAK3' => 'TO-263',
				'DDPAK5' => 'TO-263',
				'D2PAK' => 'TO-263',

				// ... widely used TO-247 (or TO-3P)
				'TO-3' => 'TO-247',
				'TO-3P' => 'TO-247',

				// SOT416: SC-75 / SOT-523
				'SOT523' => 'SOT416',
				'SC75' => 'SOT416',
				'SC75A' => 'SOT416',

				'SMV' => 'SOT25',
				'SM6' => 'SOT26',
				'SC74' => 'SOT457',

				'SMPC' => 'TO-277A',

				// Melf
				'MINIMELF' => 'MiniMELF',
				'MICROMELF' => 'MicroMELF',
				'MMA' => 'MiniMELF',
				'MMB' => 'MELF',
				'MMU' => 'MicroMELF',
			];

			if (array_key_exists($package, $normalizations)) {
				$package = $normalizations[$package];
			}
		}
		return $package;
	}
}
