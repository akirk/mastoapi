<?php
/**
 * Status handler.
 *
 * This contains the default Status handlers.
 *
 * @package Enable_Mastodon_Apps
 */

namespace Enable_Mastodon_Apps\Handler;

use Enable_Mastodon_Apps\Handler\Handler;

/**
 * This is the class that implements the default handler for all Status endpoints.
 *
 * @since 0.7.0
 *
 * @package Enable_Mastodon_Apps
 */
class Status extends Handler {
	public function __construct() {
		$this->register_hooks();
	}

	public function register_hooks() {
		add_filter( 'mastodon_api_status', array( $this, 'api_status' ), 10, 3 );
		add_filter( 'mastodon_api_statuses', array( $this, 'api_statuses' ), 10, 4 );
	}

	/**
	 * Get a status array.
	 * TODO: Replace array with Entity\Status
	 *
	 * @param array|null $status Current status array.
	 * @param int        $object_id The object ID to get the status from.
	 * @param array      $data Additional status data.
	 * @return array The status array
	 */
	public function api_status( ?array $status, int $object_id, array $data = array() ): ?array {
		$comment = get_comment( $object_id );

		if ( $comment instanceof \WP_Comment ) {
			$object_id = $comment->comment_post_ID;
		}

		$post = get_post( $object_id );

		if ( $post instanceof \WP_Post ) {
			return $this->get_status_array( $post, $data );
		}

		return $status;
	}

	/**
	 * Get a list of statuses.
	 *
	 * @param array|null $statuses Current statuses.
	 * @param array      $args Current statuses arguments.
	 * @param int|null   $min_id Optional minimum status ID.
	 * @param int|null   $max_id Optional maximum status ID.
	 * @return array
	 */
	public function api_statuses( ?array $statuses, array $args, ?int $min_id = null, ?int $max_id = null ): array {
		return $this->get_posts( $args, $min_id, $max_id );
	}
}
