/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Modal } from '@wordpress/components';
import { useSelect } from '@wordpress/data';

/*
 * Internal dependencies
 */
import { camelCaseKeys, formatAddressFields, snakeCaseKeys } from 'utils';
import { AddressStep } from 'components/address-step';
import { addressStore } from 'data/address';

interface OriginAddressModalProps {
	addressId?: string;
	onClose: () => void;
	onComplete: () => void;
}

export const OriginAddressModal = ( {
	addressId,
	onClose,
	onComplete,
}: OriginAddressModalProps ) => {
	const origins = useSelect(
		( select ) => select( addressStore ).getOriginAddresses(),
		[]
	);

	const address = origins.find( ( origin ) => origin.id === addressId );
	const isAdd = ! address;

	return (
		<Modal
			size="medium"
			onRequestClose={ onClose }
			title={
				isAdd
					? __( 'Add address', 'woocommerce-shipping' )
					: __( 'Edit address', 'woocommerce-shipping' )
			}
		>
			<AddressStep
				type={ 'origin' }
				address={ camelCaseKeys(
					formatAddressFields( snakeCaseKeys( address ?? {} ) )
				) }
				onCompleteCallback={ onComplete }
				onCancelCallback={ onClose }
				isAdd={ ! address }
				nextDesign
			/>
		</Modal>
	);
};
