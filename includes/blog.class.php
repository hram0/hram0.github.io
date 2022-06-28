<?php
if (!defined("HRAM0_INIT")) die();

class NotFoundException extends Exception {}

abstract class Url {
	const Archive = 0;
	const Page = 1;
	const Update = 3;
	const Error404 = 4;
}

class Blog {
	public $pages;
	public $url;

	private $_page_num;
	private $_page_num_total;

	function __construct($page_slug, $pagination, $tag_slug) {
		$this->_page_num = 0;
		$this->_page_num_total = 1;

		if ($page_slug !== "") {
			try {
				$this->pages = [new Page($page_slug, "")];
				$this->url = Url::Page;
			} catch (NotFoundException $e) {
				Header("HTTP/1.1 404 Not Found");
				$this->url = Url::Error404;
			}
		} else {
			$this->pages = Blog::loadPages($tag_slug);

			// Pagination configuration
			$this->_page_num = $pagination;
			$offset = Config::PagesPerPage * $this->_page_num;
			$length = Config::PagesPerPage;
			$this->_page_num_total = ceil(count($this->pages) / $length);

			// Only return the pages that appear on that page.
			$this->pages = array_slice($this->pages, $offset, $length);
			$this->url = Url::Archive;
		}
	}

	private function loadPages($tag) {
		$files = scandir(Config::PagesDirectory);
		$files = array_splice($files, 2);

		$pages = [];

		foreach ($files as $file) {
			try {
				$stem = rtrim($file, '.md');
				if (!preg_match('/[^#]+#.*/', $stem)) {
				   $pages[] = new Page($stem, $tag);
			       	}
			} catch (NotFoundException $e) {
			
			}
		}

		// Sort the pages before manipulating and displaying them
		usort($pages, function ($a, $b) {
			return ($a->timestamp > $b->timestamp) ? -1 : 1;
		});

		return $pages;
	}


	// Returns the title, depending on whether you're on a single page or not.
	public function get_title() {
		$str = "";
		if ($this->url === Url::Page) {
			$str .= $this->pages[0]->title . Config::TitleSeparator;
		}
		$str .= Config::Title;

		return $str;
	}

	/*
		PAGINATION FUNCTIONS
	*/
	public function get_page_num() {
		return $this->_page_num + 1;
	}

	public function get_page_prev() {
		return get_page_url() . "p/" . $this->_page_num;
	}

	public function get_page_next() {
		return get_page_url() . "p/" . ($this->_page_num + 2);
	}

	public function has_page_prev() {
		return ($this->_page_num === 0) ? false : true;
	}

	public function has_page_next() {
		return ($this->_page_num >= $this->_page_num_total - 1) ? false : true;
	}

	public function has_pagination() {
		return ($this->url === Url::Archive && ($this->has_page_next() || $this->has_page_prev())) ? true : false;
	}

	public function get_page_total() {
		return $this->_page_num_total;
	}
}
