<?php

namespace DocParser;

class Builder
{
    private $fileContent;

    public function __construct($className)
    {
        $this->fileContent = Tracer::extract($className);
    }

    public function build()
    {
        $tokens = token_get_all($this->fileContent);
        $action = "";
        $visible = "";
        $temp = null;
        $classFlag = false;
        $useFunction = false;
        $tree = [];
        foreach ($tokens as $t) {
            if (is_array($t)) {
                // find class doc or function doc
                if (373 === $t[0] || 371 === $t[0]) {
                    $temp = $t[1];
                }

                // check functoin doc is private or protected
                if (isset($temp) && (348 === $t[0] || 349 === $t[0])) {
                    if ("private" === $t[1] || "protected" === $t[1]) {
                        $temp = null;
                    }
                }

                // when get function name or class name
                if (isset($temp) && 308 === $t[0] && $useFunction) {
                    $parser = new Parser($temp);
                    $data   = $parser->parse();
                    if ($data) {
                        $tree[$t[1]]   = is_array($tree[$t[1]]) ? $tree[$t[1]] : [];
                        $tree[$t[1]][] = $data;
                    }

                    $useFunction = false;
                }

                if (335 === $t[0]) {
                    $useFunction = true;
                }
            }
        }

        return $tree;
    }
}
