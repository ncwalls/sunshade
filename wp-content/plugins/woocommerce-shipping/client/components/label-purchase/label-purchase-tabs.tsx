import {
	Button,
	Flex,
	FlexItem,
	Icon,
	TabPanel,
	__experimentalText as Text,
	__experimentalSpacer as Spacer,
} from '@wordpress/components';
import { forwardRef, useEffect, useCallback } from '@wordpress/element';
import { getSubItems } from 'utils/order-items';
import { useLabelPurchaseContext } from 'context/label-purchase';
import { ShipmentItem } from 'types';
import { ShipmentContent } from './shipment-content';
import { getConfig, getCurrentOrder, getCurrentOrderItems } from 'utils';
import { getShipmentTitle } from './utils';
import { __ } from '@wordpress/i18n';
import { check } from '@wordpress/icons';
import { ShipmentContentV2 } from './design-next/shipment-content-v2';
import { curvedInfo } from 'components/icons';
interface LabelPurchaseTabsProps {
	setStartSplitShipment: ( startSplitShipment: boolean ) => void;
}
export const LabelPurchaseTabs = forwardRef(
	( { setStartSplitShipment }: LabelPurchaseTabsProps, ref ) => {
		const orderItems = getCurrentOrderItems();
		const order = getCurrentOrder();
		const count = order.total_line_items_quantity;
		const {
			shipment: {
				shipments,
				setShipments,
				selections,
				setSelection,
				currentShipmentId,
				setCurrentShipmentId,
				getShipmentType,
			},
			packages,
			customs: { updateCustomsItems },
			labels: {
				isAnyRequestInProgress,
				hasMissingPurchase,
				hasUnfinishedShipment,
				isPurchasing,
				isUpdatingStatus,
				getShipmentsWithoutLabel,
				hasPurchasedLabel,
				labelStatusUpdateErrors,
				getCurrentShipmentLabel,
			},
			nextDesign,
		} = useLabelPurchaseContext();

		const orderFulfilled = ! hasMissingPurchase();
		const hasLabel = hasPurchasedLabel();
		const selectedLabel = getCurrentShipmentLabel();
		const orderId = getConfig().order.id;

		// Navigate after purchase completion (must be in useEffect to avoid setState during render)
		useEffect( () => {
			if (
				nextDesign &&
				isAnyRequestInProgress &&
				hasLabel &&
				labelStatusUpdateErrors.length === 0 &&
				window.WCShipping_Config?.navigate
			) {
				window.WCShipping_Config.navigate( {
					to: '..', // Navigate up one level to the order details page
					params: { orderId },
					search: {
						label: 'purchased',
						labelId: selectedLabel?.labelId,
					},
				} );
			}
		}, [
			nextDesign,
			isAnyRequestInProgress,
			hasLabel,
			labelStatusUpdateErrors.length,
			selectedLabel?.labelId,
			orderId,
		] );

		const navigateToOrderDetails = useCallback( () => {
			if ( window.WCShipping_Config?.navigate ) {
				window.WCShipping_Config.navigate( {
					to: '/woocommerce/orders/view/$orderId',
					params: { orderId },
				} );
			} else {
				const currentPath = window.location.pathname;
				const baseUrl = currentPath.substring(
					0,
					currentPath.lastIndexOf( '/fulfill' ) > -1
						? currentPath.lastIndexOf( '/fulfill' )
						: currentPath.length
				);
				window.location.href = baseUrl;
			}
		}, [ orderId ] );

		// This should be added after the logic that redirects after purchase completion.
		if ( orderFulfilled && nextDesign ) {
			return (
				<Flex
					direction="column"
					gap={ 0 }
					align="center"
					justify="center"
				>
					<Icon icon={ curvedInfo } size={ 40 } />
					<Spacer margin={ 6 } />
					<FlexItem>
						<Text weight={ 500 }>
							{ __(
								'This order has already been fulfilled',
								'woocommerce-shipping'
							) }
						</Text>
					</FlexItem>
					<FlexItem>
						<Text variant="muted">
							{ __(
								"You can't create a new shipping label for this order.",
								'woocommerce-shipping'
							) }
						</Text>
					</FlexItem>
					<Spacer margin={ 6 } />
					<FlexItem>
						<Button
							variant="secondary"
							onClick={ navigateToOrderDetails }
						>
							{ __(
								'Back to the order details',
								'woocommerce-shipping'
							) }
						</Button>
					</FlexItem>
				</Flex>
			);
		}

		const tabs = () => {
			let extraTabs: { name: string; title: string }[] = [];
			if (
				! orderFulfilled &&
				! isPurchasing &&
				! isUpdatingStatus &&
				count > 1
			) {
				extraTabs = [
					{
						name: 'edit',
						title: __( 'Split shipment', 'woocommerce-shipping' ),
					},
				];
			} else if ( hasUnfinishedShipment() ) {
				extraTabs = [];
			}
			if (
				getShipmentsWithoutLabel()?.length === 0 &&
				! isPurchasing &&
				! isUpdatingStatus
			) {
				extraTabs = [
					{
						name: 'new-shipment',
						title: __( 'Add shipment', 'woocommerce-shipping' ),
					},
				];
			}
			return [
				...Object.keys( shipments ).map( ( name ) => ( {
					name,
					title: getShipmentTitle(
						name,
						Object.keys( shipments ).length,
						getShipmentType( `${ name }` )
					),
					icon: (
						<>
							{ getShipmentTitle(
								name,
								Object.keys( shipments ).length,
								getShipmentType( `${ name }` )
							) }
							{ hasPurchasedLabel( true, true, name ) && (
								<Icon icon={ check } />
							) }
						</>
					),
					className: `shipment-tab-${ name }`,
				} ) ),
				...extraTabs,
			];
		};

		/**
		 * Create shipment for extra label.
		 * This function is creating or initiating the shipment data for the first time when "Add Shipment" button is clicked.
		 */
		const createShipmentForExtraLabel = async () => {
			const newShipmentId = Object.keys( shipments ).length;
			const newShipment = orderItems.map( ( orderItem ) => ( {
				...orderItem,
				subItems: getSubItems( orderItem as ShipmentItem ),
			} ) );
			const updatedShipments = {
				...shipments,
				[ newShipmentId ]: newShipment,
			};

			// The first initiation data when "Add Shipment" button is clicked.
			setShipments( updatedShipments );
			setSelection( {
				...selections,
				[ newShipmentId ]: newShipment,
			} );
			setCurrentShipmentId( `${ newShipmentId }` );

			const selectedPackage = packages.getSelectedPackage();
			if ( selectedPackage ) {
				packages.setSelectedPackage( selectedPackage );
			}

			updateCustomsItems();
		};
		return nextDesign ? (
			<ShipmentContentV2 items={ shipments[ currentShipmentId ] } />
		) : (
			<TabPanel
				key={ currentShipmentId }
				ref={ ref }
				selectOnMove={ true }
				className="shipment-tabs"
				tabs={ tabs() }
				initialTabName={ currentShipmentId }
				onSelect={ ( tabName ) => {
					/**
					 * storing the previous tab name to prevent jumping to a new tab
					 * when the user clicks on the "Edit shipments" tab
					 */
					if ( tabName === 'edit' ) {
						setStartSplitShipment( true );
					} else if ( tabName === 'new-shipment' ) {
						createShipmentForExtraLabel();
					} else {
						setCurrentShipmentId( tabName );
					}
				} }
				children={ () => (
					<ShipmentContent items={ shipments[ currentShipmentId ] } />
				) }
			/>
		);
	}
);
