<?php
/**
 * Created by PhpStorm.
 * User: Manlin
 * Date: 2019/9/8
 * Time: 下午12:55
 */
namespace EasySwoole\Consul\Request;

use EasySwoole\Spl\SplBean;

class BaseCommand extends SplBean
{
    /**
     * @var string
     */
    protected $url;// 请求的变化部分

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Map Consul API keys to bean property names.
     * return ['apiKey' => 'beanProperty']
     *
     * @return array
     */
    protected function setKeyMapping(): array
    {
        return [];
    }

    /**
     * SPL 1.x compatible helper: export array with API key names.
     *
     * @param array|null $columns
     * @param mixed      $filter
     * @return array
     */
    public function toArrayWithMapping(?array $columns = null, $filter = null): array
    {
        $array = $this->toArray();
        foreach ($this->setKeyMapping() as $dataKey => $beanKey) {
            if (array_key_exists($beanKey, $array)) {
                $array[$dataKey] = $array[$beanKey];
                unset($array[$beanKey]);
            }
        }

        if ($columns) {
            $array = array_intersect_key($array, array_flip($columns));
        }

        if ($filter === self::FILTER_NOT_NULL) {
            return array_filter($array, function ($val) {
                return !is_null($val);
            });
        } elseif ($filter === self::FILTER_NOT_EMPTY) {
            return array_filter($array, function ($val) {
                if ($val === 0 || $val === '0') {
                    return true;
                }
                return !empty($val);
            });
        } elseif (is_callable($filter)) {
            return array_filter($array, $filter);
        }

        return $array;
    }
}
