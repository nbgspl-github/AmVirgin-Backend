<?php

use App\Library\Enums\Common\Constants;
use App\Models\Slider;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlidersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up ()
	{
		Schema::create('sliders', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('title')->comment('Title of this slider, might be visible in a tooltip');
			$table->string('description', 2048)->nullable()->comment('Description of this slider');
			$table->string('banner', Constants::MaxFilePathLength)->comment('Banner for the slider');
			$table->string('target', 2048)->comment('The link associated with the slider');
			$table->enum('type', [Slider::TargetType['ExternalLink'], Slider::TargetType['VideoKey']])->comment('What type of target does this banner has');
			$table->enum('section', ['shop', 'entertainment'])->comment('What section of website does this slider belong?');
			$table->tinyInteger('rating')->default(0)->comment('Rating for this banner or associated item');
			$table->boolean('active')->default(true)->comment('Whether this slider will be visible or not');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down ()
	{
		Schema::dropIfExists('sliders');
	}
}