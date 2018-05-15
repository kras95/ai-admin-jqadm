<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2017
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Product\Selection;

sprintf( 'selection' ); // for translation


/**
 * Default implementation of product selection JQAdm client.
 *
 * @package Admin
 * @subpackage JQAdm
 */
class Standard
	extends \Aimeos\Admin\JQAdm\Common\Admin\Factory\Base
	implements \Aimeos\Admin\JQAdm\Common\Admin\Factory\Iface
{
	/** admin/jqadm/product/selection/name
	 * Name of the selection subpart used by the JQAdm product implementation
	 *
	 * Use "Myname" if your class is named "\Aimeos\Admin\Jqadm\Product\Selection\Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the JQAdm class name
	 * @since 2016.04
	 * @category Developer
	 */


	/**
	 * Copies a resource
	 *
	 * @return string HTML output
	 */
	public function copy()
	{
		$view = $this->getView();

		$view->selectionData = $this->toArray( $view->item, true );
		$view->selectionBody = '';

		foreach( $this->getSubClients() as $client ) {
			$view->selectionBody .= $client->copy();
		}

		return $this->render( $view );
	}


	/**
	 * Creates a new resource
	 *
	 * @return string HTML output
	 */
	public function create()
	{
		$view = $this->getView();

		$view->selectionData = $this->getDataParams( $view );
		$view->selectionBody = '';

		foreach( $this->getSubClients() as $client ) {
			$view->selectionBody .= $client->create();
		}

		return $this->render( $view );
	}


	/**
	 * Deletes a resource
	 */
	public function delete()
	{
		parent::delete();
		$item = $this->getView()->item;

		if( $item->getType() === 'select' ) {
			$this->cleanupItems( $item->getListItems( 'product', 'default', null, false ), [] );
		}
	}


	/**
	 * Returns a single resource
	 *
	 * @return string HTML output
	 */
	public function get()
	{
		$view = $this->getView();

		$view->selectionData = $this->toArray( $view->item );
		$view->selectionBody = '';

		foreach( $this->getSubClients() as $client ) {
			$view->selectionBody .= $client->get();
		}

		return $this->render( $view );
	}


	/**
	 * Saves the data
	 */
	public function save()
	{
		$view = $this->getView();
		$context = $this->getContext();

		$manager = \Aimeos\MShop\Factory::createManager( $context, 'product' );
		$manager->begin();

		try
		{
			$this->fromArray( $view->item, $this->getDataParams( $view ) );
			$view->selectionBody = '';

			foreach( $this->getSubClients() as $client ) {
				$view->selectionBody .= $client->save();
			}

			$manager->commit();
			return;
		}
		catch( \Aimeos\MShop\Exception $e )
		{
			$error = array( 'product-item-selection' => $context->getI18n()->dt( 'mshop', $e->getMessage() ) );
			$view->errors = $view->get( 'errors', [] ) + $error;
			$this->logException( $e );
		}
		catch( \Exception $e )
		{
			$error = array( 'product-item-selection' => $e->getMessage() . ', ' . $e->getFile() . ':' . $e->getLine() );
			$view->errors = $view->get( 'errors', [] ) + $error;
			$this->logException( $e );
		}

		$manager->rollback();

		throw new \Aimeos\Admin\JQAdm\Exception();
	}


	/**
	 * Returns the sub-client given by its name.
	 *
	 * @param string $type Name of the client type
	 * @param string|null $name Name of the sub-client (Default if null)
	 * @return \Aimeos\Admin\JQAdm\Iface Sub-client object
	 */
	public function getSubClient( $type, $name = null )
	{
		/** admin/jqadm/product/selection/decorators/excludes
		 * Excludes decorators added by the "common" option from the product JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to remove a decorator added via
		 * "admin/jqadm/common/decorators/default" before they are wrapped
		 * around the JQAdm client.
		 *
		 *  admin/jqadm/product/selection/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("\Aimeos\Admin\JQAdm\Common\Decorator\*") added via
		 * "admin/jqadm/common/decorators/default" to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2016.01
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/product/selection/decorators/global
		 * @see admin/jqadm/product/selection/decorators/local
		 */

		/** admin/jqadm/product/selection/decorators/global
		 * Adds a list of globally available decorators only to the product JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap global decorators
		 * ("\Aimeos\Admin\JQAdm\Common\Decorator\*") around the JQAdm client.
		 *
		 *  admin/jqadm/product/selection/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "\Aimeos\Admin\JQAdm\Common\Decorator\Decorator1" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2016.01
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/product/selection/decorators/excludes
		 * @see admin/jqadm/product/selection/decorators/local
		 */

		/** admin/jqadm/product/selection/decorators/local
		 * Adds a list of local decorators only to the product JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap local decorators
		 * ("\Aimeos\Admin\JQAdm\Product\Decorator\*") around the JQAdm client.
		 *
		 *  admin/jqadm/product/selection/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "\Aimeos\Admin\JQAdm\Product\Decorator\Decorator2" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2016.01
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/product/selection/decorators/excludes
		 * @see admin/jqadm/product/selection/decorators/global
		 */
		return $this->createSubClient( 'product/selection/' . $type, $name );
	}


	/**
	 * Deletes the removed list items and their referenced items
	 *
	 * @param array $listItems List of items implementing \Aimeos\MShop\Common\Item\Lists\Iface
	 * @param array $listIds List of IDs of the still used list items
	 */
	protected function cleanupItems( array $listItems, array $listIds )
	{
		$context = $this->getContext();
		$manager = \Aimeos\MShop\Factory::createManager( $context, 'product' );
		$listManager = \Aimeos\MShop\Factory::createManager( $context, 'product/lists' );

		$rmIds = [];
		$rmListIds = array_diff( array_keys( $listItems ), $listIds );

		foreach( $rmListIds as $rmListId ) {
			$rmIds[ $listItems[$rmListId]->getRefId() ] = null;
		}

		$search = $listManager->createSearch();
		$expr = array(
			$search->compare( '==', 'product.lists.domain', 'product' ),
			$search->compare( '==', 'product.lists.type.code', 'default' ),
			$search->compare( '==', 'product.lists.type.domain', 'product' ),
			$search->compare( '==', 'product.lists.refid', array_keys( $rmIds ) ),
		);
		$search->setConditions( $search->combine( '&&', $expr ) );
		$search->setSlice( 0, 0x7fffffff );

		foreach( $listManager->aggregate( $search, 'product.lists.refid' ) as $key => $count )
		{
			if( $count > 1 ) {
				unset( $rmIds[$key] );
			}
		}

		$listManager->deleteItems( $rmListIds  );
		$manager->deleteItems( array_keys( $rmIds )  );
	}


	/**
	 * Creates a new pre-filled item
	 *
	 * @return \Aimeos\MShop\Product\Item\Iface New product item object
	 */
	protected function createItem()
	{
		$context = $this->getContext();
		$manager = \Aimeos\MShop\Factory::createManager( $context, 'product' );
		$typeManager = \Aimeos\MShop\Factory::createManager( $context, 'product/type' );

		$item = $manager->createItem();
		$item->setTypeId( $typeManager->findItem( 'default', [], 'product' )->getId() );
		$item->setStatus( 1 );

		return $item;
	}


	/**
	 * Creates a new pre-filled list item
	 *
	 * @param string $id Parent ID for the new list item
	 * @return \Aimeos\MShop\Common\Item\Lists\Iface New list item object
	 */
	protected function createListItem( $id )
	{
		$context = $this->getContext();
		$manager = \Aimeos\MShop\Factory::createManager( $context, 'product/lists' );
		$typeManager = \Aimeos\MShop\Factory::createManager( $context, 'product/lists/type' );

		$item = $manager->createItem();
		$item->setTypeId( $typeManager->findItem( 'default', [], 'product' )->getId() );
		$item->setDomain( 'product' );
		$item->setParentId( $id );
		$item->setStatus( 1 );

		return $item;
	}


	/**
	 * Returns the products for the given codes and IDs
	 *
	 * @param array $codes List of product codes
	 * @param array $ids List of product IDs
	 * @return array List of products with ID as key and items implementing \Aimeos\MShop\Product\Item\Iface as values
	 */
	protected function getProductItems( array $codes, array $ids )
	{
		$manager = \Aimeos\MShop\Factory::createManager( $this->getContext(), 'product' );

		$search = $manager->createSearch();
		$expr = array(
			$search->compare( '==', 'product.id', $ids ),
			$search->compare( '==', 'product.code', $codes ),
		);
		$search->setConditions( $search->combine( '||', $expr ) );
		$search->setSlice( 0, 0x7fffffff );

		return $manager->searchItems( $search, array( 'attribute' ) );
	}


	/**
	 * Maps the input parameter to an associative array as expected by the template
	 *
	 * @param \Aimeos\MW\View\Iface $view View object with helpers and assigned parameters
	 * @return array Multi-dimensional associative array
	 */
	protected function getDataParams( \Aimeos\MW\View\Iface $view )
	{
		$data = [];
		$siteid = $this->getContext()->getLocale()->getSiteId();

		foreach( (array) $view->param( 'selection/product.code', [] ) as $pos => $code )
		{
			if( !empty( $code ) )
			{
				$data[$code]['product.lists.siteid'] = $siteid;
				$data[$code]['product.lists.id'] = $view->param( 'selection/product.lists.id/' . $pos );
				$data[$code]['product.label'] = $view->param( 'selection/product.label/' . $pos );
				$data[$code]['product.id'] = $view->param( 'selection/product.id/' . $pos );
			}
		}

		foreach( (array) $view->param( 'selection/attr/ref', [] ) as $pos => $code )
		{
			if( !empty( $code ) )
			{
				$id = $view->param( 'selection/attr/id/' . $pos );

				$data[$code]['attr'][$id]['ref'] = $code;
				$data[$code]['attr'][$id]['siteid'] = $siteid;
				$data[$code]['attr'][$id]['label'] = $view->param( 'selection/attr/label/' . $pos );
			}
		}

		return $data;
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of JQAdm client names
	 */
	protected function getSubClientNames()
	{
		/** admin/jqadm/product/selection/standard/subparts
		 * List of JQAdm sub-clients rendered within the product selection section
		 *
		 * The output of the frontend is composed of the code generated by the JQAdm
		 * clients. Each JQAdm client can consist of serveral (or none) sub-clients
		 * that are responsible for rendering certain sub-parts of the output. The
		 * sub-clients can contain JQAdm clients themselves and therefore a
		 * hierarchical tree of JQAdm clients is composed. Each JQAdm client creates
		 * the output that is placed inside the container of its parent.
		 *
		 * At first, always the JQAdm code generated by the parent is printed, then
		 * the JQAdm code of its sub-clients. The order of the JQAdm sub-clients
		 * determines the order of the output of these sub-clients inside the parent
		 * container. If the configured list of clients is
		 *
		 *  array( "subclient1", "subclient2" )
		 *
		 * you can easily change the order of the output by reordering the subparts:
		 *
		 *  admin/jqadm/<clients>/subparts = array( "subclient1", "subclient2" )
		 *
		 * You can also remove one or more parts if they shouldn't be rendered:
		 *
		 *  admin/jqadm/<clients>/subparts = array( "subclient1" )
		 *
		 * As the clients only generates structural JQAdm, the layout defined via CSS
		 * should support adding, removing or reordering content by a fluid like
		 * design.
		 *
		 * @param array List of sub-client names
		 * @since 2016.01
		 * @category Developer
		 */
		return $this->getContext()->getConfig()->get( 'admin/jqadm/product/selection/standard/subparts', [] );
	}


	/**
	 * Creates new and updates existing items using the data array
	 *
	 * @param \Aimeos\MShop\Product\Item\Iface $item Product item object without referenced domain items
	 * @param array $data Data array
	 */
	protected function fromArray( \Aimeos\MShop\Product\Item\Iface $item, array $data )
	{
		if( $item->getType() !== 'select' ) {
			return;
		}

		$context = $this->getContext();
		$manager = \Aimeos\MShop\Factory::createManager( $context, 'product' );
		$listManager = \Aimeos\MShop\Factory::createManager( $context, 'product/lists' );

		$product = $manager->getItem( $item->getId(), array( 'product' ) );
		$refItems = $product->getRefItems( 'product', null, 'default', false );
		$listItems = $product->getListItems( 'product', 'default', null, false );

		$products = $this->getProductItems( array_keys( $data ), array_keys( $refItems ) );
		$listItem = $this->createListItem( $item->getId() );
		$prodItem = $this->createItem();
		$listIds = [];
		$pos = 0;


		foreach( $data as $code => $list )
		{
			if( $code == '' ) { continue; }

			$listid = $this->getValue( $list, 'product.lists.id' );

			if( !isset( $listItems[$listid] ) )
			{
				$litem = clone $listItem;
				$item = clone $prodItem;
				$item->setId( $this->getValue( $list, 'product.id' ) );
			}
			else
			{
				$litem = $listItems[$listid];
				$item = $litem->getRefItem();
			}

			$item->setLabel( $this->getValue( $list, 'product.label', '' ) );
			$item->setCode( $code );

			$item = $manager->saveItem( $item );

			$litem->setPosition( $pos++ );
			$litem->setRefId( $item->getId() );

			$listManager->saveItem( $litem, false );

			$variant = ( isset( $products[$item->getId()] ) ? $products[$item->getId()] : $item );
			$attr = ( isset( $list['attr'] ) ? (array) $list['attr'] : [] );

			$manager->updateListItems( $variant, $attr, 'attribute', 'variant' );

			$listIds[] = $listid;
		}

		$this->cleanupItems( $listItems, $listIds );
	}


	/**
	 * Constructs the data array for the view from the given item
	 *
	 * @param \Aimeos\MShop\Product\Item\Iface $item Product item object including referenced domain items
	 * @param boolean $copy True if items should be copied
	 * @return string[] Multi-dimensional associative list of item data
	 */
	protected function toArray( \Aimeos\MShop\Product\Item\Iface $item, $copy = false )
	{
		$data = [];
		$context = $this->getContext();
		$variants = $item->getRefItems( 'product', null, 'default', false );
		$manager = \Aimeos\MShop\Factory::createManager( $context, 'product' );

		$search = $manager->createSearch();
		$search->setConditions( $search->compare( '==', 'product.id', array_keys( $variants ) ) );
		$search->setSlice( 0, 0x7fffffff );

		$products = $manager->searchItems( $search, array( 'attribute' ) );

		foreach( $item->getListItems( 'product', 'default', null, false ) as $listItem )
		{
			if( ( $refItem = $listItem->getRefItem() ) === null ) {
				continue;
			}

			$code = $refItem->getCode();
			$data[$code]['product.id'] = $listItem->getRefId();
			$data[$code]['product.label'] = $refItem->getLabel();
			$data[$code]['product.lists.siteid'] = $refItem->getSiteId();

			if( $copy === false ) {
				$data[$code]['product.lists.id'] = $listItem->getId();
			} else {
				$data[$code]['product.lists.id'] = '';
			}

			if( isset( $products[$refItem->getId()] ) )
			{
				$attributes = $products[$refItem->getId()]->getRefItems( 'attribute', null, 'variant', false );

				foreach( $attributes as $attrid => $attrItem )
				{
					$data[$code]['attr'][$attrid]['ref'] = $code;
					$data[$code]['attr'][$attrid]['label'] = $attrItem->getLabel();
					$data[$code]['attr'][$attrid]['siteid'] = $listItem->getSiteId();
				}
			}
		}

		return $data;
	}


	/**
	 * Returns the rendered template including the view data
	 *
	 * @param \Aimeos\MW\View\Iface $view View object with data assigned
	 * @return string HTML output
	 */
	protected function render( \Aimeos\MW\View\Iface $view )
	{
		/** admin/jqadm/product/selection/template-item
		 * Relative path to the HTML body template of the selection subpart for products.
		 *
		 * The template file contains the HTML code and processing instructions
		 * to generate the result shown in the body of the frontend. The
		 * configuration string is the path to the template file relative
		 * to the templates directory (usually in admin/jqadm/templates).
		 *
		 * You can overwrite the template file configuration in extensions and
		 * provide alternative templates. These alternative templates should be
		 * named like the default one but with the string "default" replaced by
		 * an unique name. You may use the name of your project for this. If
		 * you've implemented an alternative client class as well, "default"
		 * should be replaced by the name of the new class.
		 *
		 * @param string Relative path to the template creating the HTML code
		 * @since 2016.04
		 * @category Developer
		 */
		$tplconf = 'admin/jqadm/product/selection/template-item';
		$default = 'product/item-selection-standard.php';

		return $view->render( $view->config( $tplconf, $default ) );
	}
}