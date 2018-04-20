<?php
/**
 * Copyright (c) 2018 - Clarastars, LLC - All Rights Reserved.
 *
 * Unauthorized copying of this file via any medium is strictly prohibited.
 * This file is a proprietary of Clarastars LLC and is confidential.
 *
 * https://clarastars.com - info@clarastars.com
 *
 */

namespace App\Clarimount\Repository;

use App\Models\Item;
use App\Models\ItemCategory;
use App\Helpers\TreeTraversal;
use App\Models\Manufacturer;
use App\Models\UserActivity;

class ItemRepository
{
	
	protected $model;

	function __construct(Item $model)
	{
		$this->model = $model;
	}

	public function all()
	{
		return $this->model->all();
	}

	public function paginatedIndex($per_page)
	{
		return $this->model
						->latest()
						->with([
								'asset_template' => function($q) {
						            $q->with('manufacturer', 'category');
                                }
								])
						->paginate($per_page);
	}

	public function categories()
	{
		$categories = [];

		// $root_nodes = AssetCategory::where('parent', null)
		// 							->orderBy('left', 'asc')
		// 							->get()->toArray();		


		// foreach($root_nodes as &$root_node) {

		// 	$children = AssetCategory::whereBetween('left', [
		// 													$root_node['left'],
		// 													$root_node['right']
		// 												])
		// 							->orderBy('left', 'asc')->get()->toArray();

		// 	$categories[] = $children;
		// }

		$categories = ItemCategory::orderBy('left', 'asc')->get();

		return $categories;
	}

	public function destroy($id)
	{
	    $assetJson = Item::where('id', $id)->first()->toArray();

        $activity = new UserActivity();
        $activity->user_id = auth()->user()->id;
        $activity->target_id = $id;
        $activity->target_entity = 'ASSET';
        $activity->action_type = 'ASSET_DELETE';
        $activity->before_changes_json = json_encode($assetJson);
        $activity->save();

		return $this->model->where('id', $id)->delete();
	}

	public function create($model)
	{
	    $manufacturer_name = Manufacturer::where('id', $model['manufacturer_id'])->first()->title;
	    $model['search_helper'] = str_slug($manufacturer_name);
	    $model['manufacturer_name'] = $manufacturer_name;

        $activity = new UserActivity();
        $activity->user_id = auth()->user()->id;
        $activity->target_entity = 'ASSET';
        $activity->action_type = 'ASSET_CREATE';
        $activity->before_changes_json = json_encode($model);
        $activity->save();

		return $this->model->create($model);
	}

	public function find($id)
	{
	    return $this->model->where('id', $id)->firstOrFail();
	}

	public function update($id, $model)
	{
	    $asset = $this->model->find($id, ['id']);

	    foreach($model as $key => $value) {
	        $asset->$key = $value;
        }

        $asset->save();

        return $asset;
	}

}
