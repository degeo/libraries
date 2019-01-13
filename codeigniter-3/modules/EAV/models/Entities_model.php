<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Entities_model extends CRUD_model {

	protected $primary_table = 'entities';
	protected $primary_key = 'entity_id';

	public function read_entities( array $record_data = array(), $condition_type = 'AND', $limit = 20, $offset = 0, $orderby = '', $order = 'ASC' ) {
		$conditions = '1=1';

		$query = "SELECT *
					FROM {$this->primary_table}
					JOIN eav USING({$this->primary_key})
					JOIN classifications USING(classification_id)
					JOIN attributes ON(eav.attribute_id = attributes.attribute_id)
					JOIN `values` USING(`value_id`)";

		if( !empty( $record_data ) ):
			$conditions = $this->array_to_eav_sql_string( $record_data, $condition_type );

			if( !empty( $conditions ) ):
				$query .= " WHERE {$conditions}";
			endif;
		endif;

		$query = $this->groupby( $query, 'entity_id', $order );

		if( !empty( $orderby ) )
			$query = $this->orderby( $query, $orderby, $order );

		if( !empty( $limit ) )
			$query = $this->limit( $query, $limit, $offset );

		if( $limit == 1 ):
			$results = $this->get( 'result', $query );
			if( array_key_exists( 'entity_id', $results ) ):
				return $this->read_entity_values( $results['entity_id'] );
			endif;
		else:
			$results = $this->get( 'results', $query );
		endif;

		$entities = array();
		foreach( $results as $entity ):
			$presets = array( 'entity_id' => $entity['entity_id'] );
			$values = $this->read_entity_values( $entity['entity_id'] );
			$entities[] = array_merge( $presets, $values );
		endforeach;

		return $entities;
	} // function

	public function read_entity( $entity_id ) {
		if( empty( $entity_id ) )
			return array();

		$entity_id = $this->clean_value( $entity_id );

		$query = "SELECT *
					FROM {$this->primary_table}
					JOIN eav USING(entity_id)
					JOIN attributes ON(eav.attribute_id = attributes.attribute_id)
					JOIN `values` USING(`value_id`)
					WHERE entity_id = {$entity_id}";

		$results = $this->get( 'results', $query );

		$parsed_results = array();
		foreach( $results as $result ):
			$parsed_results[ $result['attribute_key'] ] = $result;
		endforeach;

		return $parsed_results;
	} // function

	public function read_entity_values( $entity_id ) {
		if( empty( $entity_id ) )
			return array();

		$entity_id = $this->clean_value( $entity_id );

		$query = "SELECT *
			FROM {$this->primary_table}
					JOIN eav USING(entity_id)
					JOIN attributes ON(eav.attribute_id = attributes.attribute_id)
					JOIN `values` USING(`value_id`)
					WHERE entity_id = {$entity_id}";

		$results = $this->get( 'results', $query );

		$parsed_results = array();
		foreach( $results as $result ):
			$parsed_results[ $result['attribute_key'] ] = $result['value'];
		endforeach;

		return $parsed_results;
	} // function

	public function read_classification( $classification ) {
		if( empty( $classification ) )
			return array();

		$classification = $this->clean_value( $classification );

		$query = "SELECT *
					FROM classifications
					JOIN entities USING(classification_id)
					WHERE classification_key = {$classification}";

		$entities = $this->get( 'results', $query );

		$results = array();
		foreach( $entities as $entity ):
			$results[] = $this->read_entity_values( $entity['entity_id'] );
		endforeach;

		return $results;
	} // function

	public function read_entity_by_attribute_value( $attribute_key, $value ) {
		if( empty( $attribute_key ) || empty( $value ) )
			return array();

		$attribute_key = $this->clean_value( $attribute_key );
		$value = $this->clean_value( $value );

		$query = "SELECT entity_id
					FROM {$this->primary_table}
					JOIN eav USING(entity_id)
					JOIN attributes ON(eav.attribute_id = attributes.attribute_id)
					JOIN `values` USING(`value_id`)
					WHERE (attribute_key = {$attribute_key} AND `value` = {$value})";

		$entity_id = $this->get( 'first_column', $query );

		return $this->read_entity_values( $entity_id );
	} // function

	public function read_entities_by_attribute_value( $attribute_key, $value ) {
		if( empty( $attribute_key ) || empty( $value ) )
			return array();

		$attribute_key = $this->clean_value( $attribute_key );
		$value = $this->clean_value( $value );

		$query = "SELECT *
					FROM {$this->primary_table}
					JOIN eav USING(entity_id)
					JOIN attributes ON(eav.attribute_id = attributes.attribute_id)
					JOIN `values` USING(`value_id`)
					WHERE entity_type = 'page'
					AND (attribute_key = {$attribute_key} AND `value` = {$value})";

		$entities = $this->get( 'results', $query );

		$results = array();
		foreach( $entities as $entity ):
			$results[] = $this->read_entity_values( $entity['entity_id'] );
		endforeach;

		return $results;
	} // function

	public function get_entities_by_object_id( $object, $id ) {
		return $this->get_relational_object_by_id( $object, $id );
	} // function

} // class
