import {
	Animate,
	Card,
	CardBody,
	Flex,
	Icon,
	__experimentalText as Text,
} from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';

import { ShippingRates } from 'components/label-purchase/shipping-service';
import { useLabelPurchaseContext } from 'context/label-purchase';
import { labelPurchaseStore } from 'data/label-purchase';
import { isEmpty } from 'lodash';
import { useCollapsibleCard } from '../internal/useCollapsibleCard';
import { Badge } from 'components/wp';
import { NoRatesAvailableV2 } from '../internal/no-rates-available-v2';
import { curvedInfo } from 'components/icons';
import { withBoundaryNext } from 'components/HOC/error-boundary/with-boundary-next';

const ShippingRatesCardComponent = () => {
	const {
		shipment: { currentShipmentId },
		rates: { isFetching },
		packages: { isCustomPackageTab, isPackageSpecified },
		hazmat: { getShipmentHazmat },
	} = useLabelPurchaseContext();
	const availableRates = useSelect(
		( select ) =>
			select( labelPurchaseStore ).getRatesForShipment(
				currentShipmentId
			),
		[ currentShipmentId ]
	);
	const { CardHeader, isOpen } = useCollapsibleCard( true );
	return (
		<Card>
			<CardHeader iconSize={ 'small' } isBorderless>
				<Flex direction={ 'row' } align="space-between">
					<Text as="span" weight={ 500 } size={ 15 }>
						{ __( 'Shipping rates', 'woocommerce-shipping' ) }
					</Text>
					{ ! isOpen && ! isPackageSpecified() && (
						<Badge intent="warning">
							{ __(
								'Missing package info',
								'woocommerce-shipping'
							) }
						</Badge>
					) }
				</Flex>
			</CardHeader>
			{ isOpen && (
				<CardBody style={ { paddingTop: 0 } }>
					{ ! Boolean( availableRates ) && (
						<Animate type={ isFetching ? 'loading' : undefined }>
							{ ( { className } ) => (
								<NoRatesAvailableV2 className={ className } />
							) }
						</Animate>
					) }
					{ availableRates && isEmpty( availableRates ) && (
						<Animate type={ isFetching ? 'loading' : undefined }>
							{ ( { className } ) => (
								<Flex
									direction="column"
									align="center"
									justify="center"
									gap={ 4 }
									className={ className }
								>
									<Icon icon={ curvedInfo } size={ 64 } />

									<Text
										align="center"
										variant="muted"
										style={ { maxWidth: '450px' } }
									>
										{ getShipmentHazmat().isHazmat && (
											<>
												{ __(
													'No rates found for this HAZMAT shipment. Adjust your shipment details and try again.',
													'woocommerce-shipping'
												) }
											</>
										) }
										{ ! getShipmentHazmat().isHazmat && (
											<>
												{ isCustomPackageTab()
													? __(
															'No rates available for this shipment. Check your package details, total weight, or destination address.',
															'woocommerce-shipping'
													  )
													: __(
															'No rates available for this shipment. Check the total weight, destination address, or try another carrier package.',
															'woocommerce-shipping'
													  ) }
											</>
										) }
									</Text>
								</Flex>
							) }
						</Animate>
					) }

					{ Boolean( availableRates ) &&
						! isEmpty( availableRates ) &&
						( isFetching ? (
							<Animate type="loading">
								{ ( { className } ) => (
									<ShippingRates
										availableRates={ availableRates }
										isFetching={ isFetching }
										className={ className }
									/>
								) }
							</Animate>
						) : (
							<ShippingRates
								availableRates={ availableRates }
								isFetching={ isFetching }
							/>
						) ) }
				</CardBody>
			) }
		</Card>
	);
};

export const ShippingRatesCard = withBoundaryNext(
	ShippingRatesCardComponent
)();
