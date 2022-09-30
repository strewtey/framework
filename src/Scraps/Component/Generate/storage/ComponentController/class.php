<?php
@command
namespace App\Components@namespace;
use Scraps\Component\ComponentEssent;
/**
 * 
 */
class @className extends ComponentEssent
{

	private $config = [
		'assets' => [
			'css' => [
				'auto_generate' => true,
				'path' => ['css.excepts.components.@locate', 'tailwind' => 'https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css'],
				'exceptions' => ['tailwind']
			],
			'js' => [
				'auto_generate' => true,
				'path' => ['js.excepts.components.@locate', 'jquery' => 'https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js'],
				'exceptions' => ['jquery']
			]
		]
	];

	private $class;
	private $attribute;
	private $id;
	private $messages;

	// properties...
	@moreInit

	/**
	 * Create a new component instance.
	 *
	 * @return void
	 */
	public function __construct(@moreParamsarray $messages = [], string|null $id = null, string|null $class = null, string|null $attribute = null){
		$this->messages = $messages;
		$this->id = $id;
		$this->class = $class;
		$this->attribute = $attribute;
		
		// properties...
		@moreConst
	}

	public function render(){
		$this->componentRender('components.@locate', [
			'messages' => $this->messages,
			'id' => $this->id,
			'class' => $this->class,
			'attribute' => $this->attribute,

			// properties...
			@moreRender
		]);
	}

	public function getConfig(string|null $key = null){
		return empty($key) ? $this->config : $this->config[$key];
	}
}
