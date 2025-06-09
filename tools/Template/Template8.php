<?php

    namespace Tools\Template;
//----------------------------------------------------------------------
// (c) 2021 AK
// Interface functions:
//	template( $tpl, [$isstring] ) -- construct template from file [or string] $tpl
//	reset()                       -- reset to non-modified stage function
//	cut( $mark, [$val] )          -- cut/replace block
//	dynamic( $mark, [$val] )      -- assign-insert dynamic block
//	assign( $mark, [$val] )       -- assign-insert a val; $mark: string or array
//	fetch()                       -- var output function
//	output()                      -- screen output function

    class Template8
    {
        private string|null|array $page;
        private string|null|array $pageOrig;
        private array $dynamics = [];

        public static function create(string $tplName): Template8
        {
            return new self($tplName);
        }

        public function __construct($tpl, $isString = 0)
        {
            if (!$isString) {
                if (is_file($tpl)) {
                    $f = fopen($tpl, "r");
                    $this->page = fread($f, filesize($tpl));
                    fclose($f);
                } else {
                    $this->page = 'нет шаблона '.$tpl;
                }
            } else { // make from string
                $this->page = $tpl;
            }
            $this->pageOrig = $this->page;
        }

        //	reset to non-modified buffer function ----------------------------------
        public function reset(): static
        {
            $this->page = $this->pageOrig;
            $this->dynamics = array();
            return $this;
        }

        //	cut/replace frame from ($mark)_BEGIN to ($mark)_END function -----------
        public function cut($mark, $val = ''): static
        {
            if (empty($mark)) return $this;;
            $b = $mark . '_BEGIN';
            $e = $mark . '_END';
            $val = str_replace('$', '\$', $val);
            $this->page = preg_replace("/\{$b\}.*?\{$e\}/ms", $val, $this->page);
            return $this;
        }

        function true_false($mark, bool $val): static
        {
            if (empty($mark)) return $this;
            $t = $mark . '_TRUE';
            $f = $mark . '_FALSE';
            $e = $mark . '_END';
            if ($val) {
                $this->page = preg_replace("/\{$f\}.*?\{$e\}/ms", '', $this->page);
            }
            else {
                $this->page = preg_replace("/\{$t\}.*?\{$f\}/ms", '', $this->page);
            }
        }

        private function cut_mark($mark): static
        {
            if (empty($mark)) return $this;
            $b = $mark . '_BEGIN';
            $e = $mark . '_END';
            $this->page = preg_replace("/\{$b\}/ms", '', $this->page);
            $this->page = preg_replace("/\{$e\}/ms", '', $this->page);
            return $this;
        }

        public function cut_dynamic($mark, $val = ''): static
        {
            if (empty($mark)) return $this;
            $b = $mark . '_DYNAMIC';
            $e = $mark . '_END';
            $val = str_replace('$', '\$', $val);
            $this->page = preg_replace("/\{$b\}.*?\{$e\}/ms", $val, $this->page);
            return $this;
        }

        //	assign-insert dynamics ($mark)_DYNAMIC to ($mark)_END ------------------
        public function dynamic($mark, $vals = ''): static
        {
            if (empty($mark)) {
                return $this;
            }
            $b = $mark . '_DYNAMIC';
            $e = $mark . '_END';
            if (!isset($this->dynamics[$mark])) { // first time block using
                $block = array();
                $i = preg_match("/\{$b\}(.*?)\{$e\}/ms", $this->page, $block);
                if (!$i) return $this; // block not found in tpl!
                $this->dynamics[$mark] = $block[1]; // store block's body into dynamics
                unset($block);
                // remove block from tpl; leave only beginning mark
                $this->page = preg_replace("/\{$b\}.*?\{$e\}/ms", '{' . $b . '}', $this->page);
            }
            if (is_array($vals)) { // insert values
                $blk = $this->dynamics[$mark];
                foreach ($vals as $k => $v) {
                    $v = str_replace('$', '\$', $v);
                    $blk = preg_replace("/\{$k\}/m", $v, $blk);
                }
                $blk = str_replace('$', '\$', $blk);
                $this->page = preg_replace("/\{$b\}/m", $blk . '{' . $b . '}', $this->page);
            }
            return $this;
        }

        //	assign-insert function -------------------------------------------------
        public function assign(array $mark): static
        {
            foreach ($mark as $k => $v) {
                $v = str_replace('$', '\$', $v);
                $this->page = preg_replace("/\{$k\}/m", $v . '{' . $k . '}', $this->page);
            }

            return $this;
        }

        //	remove extra mark function ---------------------------------------------
        private function _remove_marks(): static
        {
//            $this->page = preg_replace("/\{[A-Z][A-Z0-9_]*?\}/m", '', $this->page);
            $this->page = preg_replace("/\{[a-zA-Z0-9_]+\}/m", '', $this->page);
            $this->page = preg_replace("/<!--([\s\S]*?)-->/mi", '', $this->page);
            return $this;
        }


        //	var output function ----------------------------------------------------
        public function fetch(): string
        {
            $this->_remove_marks();
            return $this->page;
        }

    }
