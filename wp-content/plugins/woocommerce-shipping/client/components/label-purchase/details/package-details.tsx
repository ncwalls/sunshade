import { BaseControl, __experimentalText as Text } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { Label, ReturnShipmentInfo } from 'types';
import { getStoredPackageDimensions, getWeightUnit } from 'utils/config';
import { convertWeightToUnit, WEIGHT_UNITS } from 'utils';
import { useLabelPurchaseContext } from 'context/label-purchase';

interface LabelWithPackageData extends Label {
	packageWeight?: number;
	packageWeightUnit?: string;
	packageLength?: number;
	packageWidth?: number;
	packageHeight?: number;
	packageDimensionsUnit?: string;
}

interface PackageDetailsProps {
	label?: Label | null;
	returnShipmentInfo?: ReturnShipmentInfo | null;
	currentShipmentId?: string;
}

/**
 * Component that displays package dimensions and weight from a purchased label or preserved return data
 *
 * This component handles both regular shipment labels and return shipment data,
 * with proper fallback and validation logic.
 *
 * @param {PackageDetailsProps}     props                    - Component props
 * @param {Label|null}              props.label              - The purchased label data containing package information
 * @param {ReturnShipmentInfo|null} props.returnShipmentInfo - Return shipment data with preserved package info
 *
 * @return {JSX.Element|null} Package details UI or null if no data available
 *
 * @example
 * // Display package details for a regular shipment
 * <PackageDetails label={purchasedLabel} />
 *
 * @example
 * // Display package details for a return shipment
 * <PackageDetails returnShipmentInfo={returnData} />
 */
export const PackageDetails = ( {
	label,
	returnShipmentInfo,
	currentShipmentId,
}: PackageDetailsProps ) => {
	const { packages, weight } = useLabelPurchaseContext();

	// Get package dimensions from stored metadata
	const storedPackageDimensions = getStoredPackageDimensions();

	let packageData = null;

	// Try to get dimensions from stored metadata
	if ( currentShipmentId && storedPackageDimensions ) {
		const shipmentKey =
			`shipment_${ currentShipmentId }` as keyof typeof storedPackageDimensions;
		const shipmentDimensions = storedPackageDimensions[ shipmentKey ];
		if ( shipmentDimensions?.[ 0 ] ) {
			packageData = shipmentDimensions[ 0 ];
		}
	}

	// Try to get from label object (for backward compatibility)
	if ( ! packageData && label ) {
		const typedLabel = label as LabelWithPackageData;
		if (
			typedLabel.packageWeight !== undefined ||
			typedLabel.packageLength !== undefined ||
			typedLabel.packageWidth !== undefined ||
			typedLabel.packageHeight !== undefined
		) {
			packageData = {
				package_weight: typedLabel.packageWeight,
				package_weight_unit: typedLabel.packageWeightUnit,
				package_length: typedLabel.packageLength,
				package_width: typedLabel.packageWidth,
				package_height: typedLabel.packageHeight,
				package_dimensions_unit: typedLabel.packageDimensionsUnit,
			};
		}
	}

	// Fall back to preserved package data from return shipments
	if ( ! packageData && returnShipmentInfo?.originalPackageDetails ) {
		packageData = returnShipmentInfo.originalPackageDetails;
	}

	// Get current package data from context if not purchased yet
	if ( ! packageData && ! label && packages ) {
		const currentPackage = packages.getPackageForRequest();
		if ( currentPackage ) {
			// Transform package data to match expected format
			// Note: weight from context is in store's configured unit
			packageData = {
				package_weight: weight.getShipmentWeight(),
				package_weight_unit: getWeightUnit(),
				package_length: currentPackage.length,
				package_width: currentPackage.width,
				package_height: currentPackage.height,
				package_dimensions_unit: 'in',
			};
		}
	}

	if ( ! packageData ) {
		return null;
	}

	// Extract package dimensions (stored as snake_case in metadata)
	const packageWeight = packageData.package_weight;
	const packageWeightUnit = packageData.package_weight_unit;
	const packageLength = packageData.package_length;
	const packageWidth = packageData.package_width;
	const packageHeight = packageData.package_height;
	const packageDimensionsUnit = packageData.package_dimensions_unit;

	// Only render if we have at least weight or dimensions
	const hasWeight = packageWeight !== undefined && packageWeight !== null;
	const hasDimensions = Boolean(
		packageLength ?? packageWidth ?? packageHeight
	);

	if ( ! hasWeight && ! hasDimensions ) {
		return null;
	}

	const formatDimensions = () => {
		if ( ! hasDimensions ) {
			return null;
		}

		const dimensions = [ packageLength, packageWidth, packageHeight ]
			.filter( Boolean )
			.join( ' × ' );

		const unit = packageDimensionsUnit ?? 'in';
		return dimensions ? `${ dimensions } ${ unit }` : null;
	};

	const formatWeight = () => {
		if ( ! hasWeight ) {
			return null;
		}

		// Backend stores weight in oz, convert to user's preferred unit
		const storedUnit = packageWeightUnit ?? 'oz';
		const userPreferredUnit = getWeightUnit();

		// Convert weight from stored unit (oz) to user's preferred unit
		const convertedWeight = convertWeightToUnit(
			packageWeight,
			storedUnit as ( typeof WEIGHT_UNITS )[ keyof typeof WEIGHT_UNITS ],
			userPreferredUnit
		);

		const roundedWeight = Math.round( convertedWeight * 10 ) / 10;
		return `${ roundedWeight } ${ userPreferredUnit }`;
	};

	return (
		<>
			{ hasDimensions && (
				<BaseControl
					id="package-dimensions"
					label={ __( 'Package dimensions', 'woocommerce-shipping' ) }
					__nextHasNoMarginBottom={ true }
				>
					<Text>{ formatDimensions() }</Text>
				</BaseControl>
			) }

			{ hasWeight && (
				<BaseControl
					id="package-weight"
					label={ __( 'Package weight', 'woocommerce-shipping' ) }
					__nextHasNoMarginBottom={ true }
				>
					<Text>{ formatWeight() }</Text>
				</BaseControl>
			) }
		</>
	);
};
