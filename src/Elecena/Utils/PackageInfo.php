<?php

namespace Elecena\Utils;

/**
 * IC packages parsing utilities
 *
 * @see http://www.intersil.com/en/support/packaginginfo.html
 * @see http://www.interfacebus.com/semiconductor-transistor-packages.html
 * @see http://www.interfacebus.com/Design_Pack_types.html
 * @see https://www.torexsemi.com/technical-support/packages/
 * @see http://www.marsport.org.uk/smd/package%20equivalents.htm
 * @see http://www.topline.tv/Pin-Count.html
 * @see http://www.icpackage.org/
 * @see https://learn.sparkfun.com/tutorials/integrated-circuits/ic-packages
 * @see https://en.wikipedia.org/wiki/List_of_integrated_circuit_packaging_types
 * @see https://www.snapeda.com/discover/
 * @see https://www.jedec.org/standards-documents/focus/registered-outlines-jep95/transistor-outlines-archive
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

		// TO220-ISO
		$desc = str_replace('-ISO', 'ISO', $desc);

		// 24 ld QFN -> 24-QFN
		$desc = preg_replace('#(\d+) LD ([A-Z]+)#', '$1-$2', $desc);

		// "swap" package signatures / 64-LQFP -> LQFP64
		$desc = preg_replace('#(\d{1,})-\s?([2A-Z]{3,})#', '$2$1', $desc);

		// DIP 6 -> DIP-6
		$desc = preg_replace('#(\b(DIP|ZIP))\s([1-9]\d?)#', '$1-$3', $desc);

		// SOP08 -> SOP8
		$desc = preg_replace('#([A-Z]{3,})0(\d)#', '$1$2', $desc);

		// TO218AB-5pin -> TO218AB-5 pin
		$desc = preg_replace('#(\d)PIN#', '$1 PIN', $desc);

		$groups = [
			'TO-?(1|2|3|4|5|6|7|8|9|10|11|12|13|14|15|16|17|18|19|20|21|22|23|24|25|26|27|28|29|30|31|32|33|34|35|36|37|38|39|40|41|42|43|44|45|46|47|48|49|50|51|52|53|54|55|56|57|58|59|60|61|62|63|64|65|66|67|68|69|70|71|72|73|74|75|76|77|78|79|80|81|82|83|84|85|86|87|88|89|90|91|93|94|95|96|97|98|99|100|101|102|103|104|105|106|107|108|109|110|111|112|113|114|115|116|117|118|119|120|121|122|123|124|125|126|127|128|129|130|131|132|201|202|203|204|205|206|206-?AA|207|208|208A|210|211|212|213AA|214|217|218|221|222|223|224|225|226|227|228|229|230|231|232|233|234|235|239|241|242|248|249|254|255|257|258|259|264|268|276)',
			// https://www.infineon.com/cms/en/product/packages/PG-TO218/
			// https://www.infineon.com/cms/en/product/packages/PG-TO218/PG-TO218-5-146/
			'TO-?218(AB-5|-5-146)',
			// https://en.wikipedia.org/wiki/TO-92
			'TO-?92(-3)?',
			// https://en.wikipedia.org/wiki/TO-220
			'I?TO-?220(AB|AC|F|FP|SG|-3|-5|ISO|-ISO)?',
			'TOP-?(3)',
			// If more heat needs to be dissipated, devices in the also widely used TO-247 (or TO-3P) package can be selected / TO-3PF variant a slightly lower one / SOT429: TO-247
			// TO-3PN - https://easyeda.com/teeler123/component/TO_3PN-89Fb5nhzt
			'TO-?(247(AC|AD|-3)?|3|3P|3PF|3PN)',
			// https://www.nxp.com/packages/SOT82 -> SIP3; TO-220(JEDEC) / plastic single-ended package; 3 leads (in-line)
			'SOT-?(82|429)',
			// https://en.wikipedia.org/wiki/Small_Outline_Integrated_Circuit
			'(LF|L|M|P|V|VF)?QFP(N)?-?(100|128|144|176|208|32|44|48|52|64|80)',
			'DIL-?(8|14|16|18|20|22|24|28|32|36|40|42|48|64)',
			// https://en.wikipedia.org/wiki/Small_Outline_Integrated_Circuit
			'((H|HT|HTS|LS|M|S|T|TS|TV|Q|V|VS)?SOP?|SOIC)-?(4|5|6|8|10|12|14|16|18|20|24|28|30|32|36|38|44|48|54|56)(-J|-W|-EP|-POWERPAD)?',
			// https://en.wikipedia.org/wiki/Dual_in-line_package
			'(CERDIP|CDIP|PDIP|DIP|MDIP|EDIP)-?(6|8|10|12|14|16|18|20|22|24|28|32|36|40|42|48|64)',
			// Slim plastic dip" (0.3" lead spacing) versus the usual 0.4" spacing used on 28- and 40-pin packages / http://www.topline.tv/dip.html
			'SP?DIP-?(24|28|32|40|42|52|56|64)',
			'HC-?49([/-]?[US])?',
			// DO packages
			// http://www.topline.tv/DO.html / https://en.wikipedia.org/wiki/DO-204 / https://en.wikipedia.org/wiki/DO-214 / https://en.wikipedia.org/wiki/Metal_electrode_leadless_face
			// $ curl -s 'http://www.topline.tv/DO.html' | grep -oE 'DO-[0-9A-Z]+' | cut -d'-' -f 2 | sort -n | uniq | tr "\n" "|"
			'DO-?(1|4|5|6|7|8|9|10|11|12|13|14|15|16|17|18|19|20|21|22|23|24|25|26|27|28|29|30|31|32|33|34|35|36|37|38|39|40|41|41G|42|43|44|45|200AA|200AB|201AA|201AD|201AE|202AA|203AA|203AB|204AA|204AB|204AC|204AD|204AE|204AF|204AG|204AH|204AL|205AA|205AB|208AA|209AA|210AA|211AA|213AA|213AB|214AA|214AB|214AC|214BA|215|215AA|215AB|215AC|216|216AA|218AB|219|219AB|220|220AA|241AB|244AC)',
			'CB-?429',
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
			'TO-?253',
			// http://www.smdmark.com/en-US/ic-201725.html
			'T-?63',

			// The SOT-227, or sometimes referred to as the ISOTOP® package
			'(SOT-?227(B|-4)?)|ISOTOP',
			// https://en.wikipedia.org/wiki/Small-outline_transistor#SOT223_.28.3DSOT223-4.29 / http://www.nxp.com/packages/SOT223
			'SOT-?223-?(3|4|5|6|8)?',
			'(SC-?73)|(TO-?261AA)|(SM-?8)',
			// https://en.wikipedia.org/wiki/Small-outline_transistor#SOT89-3
			'SOT?-89-?(3|4|5)?',
			// SOT-93 is a through-hole mount package between TO-220 and TO-247 (TO-218 / SOT-93) / http://www.psitechnologies.com/products/to218.php
			'SOT-?93',
			// http://www.ferret.com.au/c/richardson-electronics/100v-mosfet-modules-in-sp3-sp4-sp6-packages-n679793 / mosfet modules
			'SP(1|3|4|6|6-P)',
			// Clipwatt
			'CLIPWATT(-|\s)?(11|15|19)',
			// Ball Grid Array / https://en.wikipedia.org/wiki/Ball_grid_array
			'(BGA|CABGA|CSPBGA|DSBGA|FBGA|FCBGA|FCPBGA|FPBGA|FTBGA|HBGA|PBGA|TBGA|TFBGA|TWBGA|UBGA|VFBGA)-?(4|5|6|48|63|64|90|96|108|113|119|121|132|144|165|191|208|256|324|400|480|484|672|676|780|783|896|900|1152|1156|1517|1704|1760|1932)',
			// DPAK (TO-252) / https://en.wikipedia.org/wiki/TO-263
			'(D-?PAK|D2PAK|DDPAK)-?(3|5)?',
			'TO-?(252|252-3|252-5|263|263-5)(AA|AB)?',
			// I2PAK (TO-262) PACKAGE / https://www.vishay.com/mosfets/i2pak-to-262-package/
			'I2PAK',
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
			// TO-262
			'TO-?262(AA)?',
			// SILP package / https://sites.google.com/site/nhecomponents/components-index/integrated-circuits/la-series
			'SILP?-?(7|9|10|11|12|13|14|15|16|17|18|23|25|30)',
			// ZIP - Zig-zag in-line package
			'ZIP-?(5|9|15|20|24|40)',
			// Pentawatt / https://easyeda.com/antonellapuricelli99/component/Pentawatt_V-GEZvN5nFz & https://easyeda.com/Junikos/component/PENTAWATT-lHBTNHYgy
			// http://www.marcospecialties.com/pinball-parts/VN02N
			// TO-220-5 / https://github.com/KiCad/TO_SOT_Packages_THT.pretty/blob/master/TO-220-5_Pentawatt_Multiwatt-5_Vertical_StaggeredType1.kicad_mod
			'PENTAWATT(-V)?',
			// TO-202 is a type of plastic-molded package that features a flat metal tab at its back / http://eesemi.com/to202.htm
			'TO-?202(-[13])?',
			// RD91 package is for wire connections via direct soldering or push-on terminals
			'RD-?(91)',
			// SQL package
			'SQL-?(5|9|11|12|15|17|23)',

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
			$package = str_replace('/', '', $package); # remove slashes in HC49/U
			$package = preg_replace('#([A-Z])-([A-Z\d]+)#', '$1$2', $package); # remove dash in DIL-14 -> "DIL14"
			$package = preg_replace('#(TO|DO|CLIPWATT)(\d+)#', '$1-$2', $package); # add dash to TO92, DO-14 and CLIPWATT19 -> "TO-92"

			/**
			 * Normalize packages
			 *
			 * @see http://www.topline.tv/DO.html
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

				// https://www.nxp.com/packages/SOT82 / SIP3; TO-220(JEDEC)
				'SOT82' => 'TO-220',

				// The SOT-227, or sometimes referred to as the ISOTOP® package
				'ISOTOP' => 'SOT-227',
				'SOT227' => 'SOT-227',
				'SOT227-4' => 'SOT-227',
				'SOT227B' => 'SOT-227B',

				// http://www.smdmark.com/en-US/ic-201725.html
				'T63' => 'SOT-23',

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

				// I2PAK (TO-262)
				// https://www.vishay.com/mosfets/i2pak-to-262-package/
				'I2PAK' => 'TO-262',

				// ... widely used TO-247 (or TO-3P)
				// @see http://www.nxp.com/packages/SOT429.html
				'TO-3' => 'TO-247',
				'TO-3P' => 'TO-247',
				'SOT429' => 'TO-247',

				// SOT-93 is a through-hole mount package between TO-220 and TO-247 in terms of package size | TO-218 / SOT-93
				// @see http://www.psitechnologies.com/products/to218.php
				'SOT93' => 'TO-218',

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

				// Pentawatt
				'PENTAWATTV' => 'PENTAWATT-V',
				'TO-220-5' => 'PENTAWATT',

				// http://www.irf.com/part/55V-DUAL-N-CHANNEL-DIGITAL-AUDIO-HEXFET-POWER-MOSFET-IN-A-TO-220-FULL-PAK-ISO-PACKAGE/_/A~IRFI4024H-117P
				'TO-220ISO' => 'TO-220 Full-Pak',

				// TO-206-AA
				// @see https://en.wikipedia.org/wiki/TO-18
				// @see https://www.arrow.com/en/products/2n2222a/microsemi
				'TO-206-AA' => 'TO-18',

				// @see https://www.infineon.com/cms/en/product/packages/PG-TO218/
				'TO-218AB5' => 'TO-218AB-5',

				// http://www.taydaelectronics.com/1-5ke6v8-tvs-bidirectional-6-8v-1500w-cb429-do-201ad-1-5ke6v8ca.html
				'CB429' => 'DO-201AD',
			];

			if (array_key_exists($package, $normalizations)) {
				$package = $normalizations[$package];
			}
		}
		else {
			# var_dump(__METHOD__, $desc, $package); // debug
		}
		return $package;
	}
}
