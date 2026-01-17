import type { Package } from './package';
import type { CustomPackage } from './custom-package';

export interface ReturnShipmentInfo {
	isReturn: boolean;
	parentShipmentId?: string;
	preservedPackage?: {
		packageLength?: number;
		packageWidth?: number;
		packageHeight?: number;
		packageWeight?: number;
		packageWeightUnit?: string;
		packageDimensionsUnit?: string;
		totalWeight?: number;
		isCustomPackageTab?: boolean;
		customPackage?: CustomPackage;
		selectedPackage?: Package;
		packageName?: string;
		packageId?: string | number;
	};
	/**
	 * Package dimensions and weight from the original shipment,
	 * used to pre-populate return label creation
	 */
	originalPackageDetails?: {
		package_weight?: number;
		package_weight_unit?: string;
		package_length?: number;
		package_width?: number;
		package_height?: number;
		package_dimensions_unit?: string;
	};
}
