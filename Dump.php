<?php
/**
 * Created by PhpStorm.
 * User: trad3r
 * Date: 25.03.19
 * Time: 10:42
 */
namespace trad3r\dump;

class Dump
{
    public static function show($data)
    {
        $str = "<div style='font-family: Courier New,serif;'>";
        $str .= self::addOpenningBracket($data);

        $result = self::dump($str, $data);

        $result .= "</div>";

        $result .= self::addJS();
        echo $result;
    }

    private static function dump($str, $data)
    {
        if(is_array($data) || is_object($data)){
            $str .= "<div style='padding: 5px 20px;box-sizing: border-box;'>";
            foreach ($data as $key => $item){
                $str .= "<div style='display: block; 
                        box-sizing: border-box;
                        '>[$key] => ";
                if (is_array($item) || is_object($item)){
                    $str .= self::addOpenningBracket($item);
                } else {
                    $str .= "<span style='
                    padding: 2px 10px;
                    box-sizing: border-box;
                    ";
                }
                $str = self::dump($str, $item);
                $str .= "</div>";
            }
            $str .= self::addClosingBracket($data);
        }elseif(is_numeric($data)){
            $str .= "color: red;'>". $data . "</span>";
        }else{
            $str .= 'color: green;\'>"' . $data . '"</span>';
        }
        return $str;
    }

    private static function addOpenningBracket($data)
    {
        if (is_array($data)){
            $str = "<div style='display: inline-block;
                            padding: 0 3px;
                            text-align: center;
                            border-radius: 2px;
                            border: 1px solid #950d0d;
                            font-size: 14px;
                            line-height: normal;
                            box-sizing: border-box;
                            cursor: pointer;
                            background: transparent;'
                            class='block opened'>-</div> [";
        } elseif (is_object($data)){
            $str = "<div style='display: inline-block;
                            padding: 0 3px;
                            text-align: center;
                            border-radius: 2px;
                            border: 1px solid #950d0d;
                            font-size: 14px;
                            line-height: normal;
                            box-sizing: border-box;
                            cursor: pointer;
                            background: transparent;'
                            class='block opened'>-</div> {";
        }else{
            $str = $data;
        }

        return $str;
    }

    private static function addClosingBracket($data)
    {
        return "</div>" . (is_array($data) ? "]" : "}");
    }

    private static function addJS()
    {
        return <<<JS
    <script>
        var blocks = document.querySelectorAll('.block');

        blocks.forEach(block => {
            block.addEventListener('mouseenter', opacityDecrease);
            block.addEventListener('mouseleave', opacityIncrease);
            block.addEventListener('click', hideShowInnerHtml);
        })

        function opacityDecrease()
        {
          event.target.nextElementSibling.style.opacity = 0.5;
        }

        function opacityIncrease()
        {
          event.target.nextElementSibling.style.opacity = 1;
        }
        function hideShowInnerHtml()
        {
            var el = event.target;
            if(el.innerText === "-"){
                el.nextElementSibling.style.display = 'none';
                el.innerText = "+";
            }else{
                el.nextElementSibling.style.display = 'block';
                el.innerText = "-";
            }

        }
    </script>
JS;

    }
}
