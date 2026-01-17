import apiFetch from '@wordpress/api-fetch';
import { NAMESPACE } from 'data/constants';
import { registerAddressStore } from 'data/address';
import { registerSettingsStore } from 'data/settings';
import { registerCarrierStrategyStore } from 'data/carrier-strategy';
import type { WCShippingConfig } from 'types';
import { __dangerousOptInToUnstableAPIsOnlyForCoreModules } from '@wordpress/private-apis';

let configPromise: Promise< WCShippingConfig > | null = null;

export const loadConfig = async () => {
	if ( ! window.WCShipping_Config && ! configPromise ) {
		configPromise = apiFetch< WCShippingConfig >( {
			path: NAMESPACE + '/config/settings',
		} );
		const config = await configPromise;
		window.WCShipping_Config = config;
		registerAddressStore( false );
		registerSettingsStore();
		registerCarrierStrategyStore();
	}
};

export const { lock, unlock } =
	__dangerousOptInToUnstableAPIsOnlyForCoreModules(
		'I acknowledge private features are not for use in themes or plugins and doing so will break in the next version of WordPress.',
		'@wordpress/edit-site'
	);
