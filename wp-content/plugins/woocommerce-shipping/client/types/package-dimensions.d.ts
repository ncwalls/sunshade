/**
 * Package dimensions and weight information from a shipment
 */
export interface PackageDimensions {
	/** Package weight value */
	package_weight?: number;
	/** Unit for weight (e.g., 'oz', 'lbs', 'kg', 'g') */
	package_weight_unit?: string;
	/** Package length */
	package_length?: number;
	/** Package width */
	package_width?: number;
	/** Package height */
	package_height?: number;
	/** Unit for dimensions (e.g., 'in', 'cm') */
	package_dimensions_unit?: string;
}
