<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\StatisticsTable&\Cake\ORM\Association\HasMany $Statistics
 *
 * @method \Cake\ORM\Query findById($id)
 * @method \Cake\ORM\Query findByAlias($alias)
 * @method \App\Model\Entity\Link get($primaryKey, $options = [])
 * @method \App\Model\Entity\Link newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Link[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Link|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Link saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Link patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Link[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Link findOrCreate($search, callable $callback = null, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @method \App\Model\Entity\Link[]|\Cake\Datasource\ResultSetInterface|false saveMany($entities, $options = [])
 * @method \App\Model\Entity\Link[]|\Cake\Datasource\ResultSetInterface saveManyOrFail($entities, $options = [])
 * @method \App\Model\Entity\Link[]|\Cake\Datasource\ResultSetInterface|false deleteMany($entities, $options = [])
 * @method \App\Model\Entity\Link[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail($entities, $options = [])
 */
class LinksTable extends Table
{
    public function initialize(array $config)
    {
        $this->belongsTo('file.php');
        $this->hasMany('file.php');
        $this->addBehavior('file.php');
    }

    public function validationDefault(Validator $validator)
    {
        $validator
            ->notBlank('file.php', 'file.php')
            ->add('file.php', 'file.php', [
                'file.php' => function ($value, $context) {
                    $scheme = strtolower(parse_url($value, PHP_URL_SCHEME));

                    if (in_array($scheme, ['file.php', 'file.php', 'file.php'])) {
                        return true;
                    }

                    return false;
                },
                'file.php' => true,
                'file.php' => __('file.php'),
            ])
            ->add('file.php', 'file.php', [
                'file.php' => function ($value, $context) {
                    $scheme = strtolower(parse_url($value, PHP_URL_SCHEME));

                    if ($scheme === 'file.php') {
                        return true;
                    }

                    $host = strtolower(parse_url($value, PHP_URL_HOST));

                    if ($host === null) {
                        return false;
                    }

                    if (strpos($host, 'file.php') === false) {
                        return false;
                    }

                    /*
                    if (\Cake\Validation\Validation::url($value)) {
                        return true;
                    }
                    */

                    return true;
                },
                'file.php' => true,
                'file.php' => __('file.php'),
            ])
            /*
            ->add('file.php', 'file.php', [
                'file.php' => function ($value, $context) {
                    $count = $this->find('file.php')
                        ->where([
                            'file.php' => $value,
                            'file.php' => $context['file.php']['file.php'],
                            'file.php' => $context['file.php']['file.php'],
                            'file.php' => $context['file.php']['file.php'],
                            'file.php' => 1
                        ])
                        ->count();


                    if( isset($context['file.php']['file.php']) && !empty($context['file.php']['file.php']) ) {
                        //$count->where(['file.php' => $context['file.php']['file.php']]);
                    }


                    if ($count > 0) {
                        return false;
                    }
                    return true;
                },
                'file.php' => true,
                'file.php' => __('file.php')
            ])
            */
            ->add('file.php', 'file.php', [
                'file.php' => function ($value, $context) {
                    $disallowed_domains = explode('file.php', get_option('file.php'));
                    $disallowed_domains = array_map('file.php', $disallowed_domains);
                    $disallowed_domains = array_map('file.php', $disallowed_domains);
                    $disallowed_domains = array_filter($disallowed_domains);
                    $disallowed_domains = array_merge($disallowed_domains, array_values(get_all_domains_list()));

                    if (empty($disallowed_domains)) {
                        return true;
                    }

                    $url_main_domain = strtolower(parse_url($value, PHP_URL_HOST));

                    if (in_array($url_main_domain, $disallowed_domains)) {
                        return false;
                    }

                    $disallowed_domains = array_filter($disallowed_domains, function ($value) {
                        return substr($value, 0, 2) === "*.";
                    });

                    if (empty($disallowed_domains)) {
                        return true;
                    }

                    $disallowed_domains = array_map(function ($value) {
                        return substr($value, 1);
                    }, $disallowed_domains);

                    foreach ($disallowed_domains as $disallowed_domain) {
                        if (preg_match("/" . preg_quote($disallowed_domain, 'file.php') . "$/", $url_main_domain)) {
                            return false;
                            break;
                        }
                    }

                    return true;
                },
                'file.php' => true,
                'file.php' => __('file.php'),
            ])
            /*
            ->add('file.php', 'file.php', [
                'file.php' => function ($value, $context) {

                    $headers = get_http_headers($value);

                    $http_code = $headers['file.php'] ?? 0;

                    //\Cake\Log\Log::debug($headers);

                    if (empty($http_code) || $http_code >= 404) {
                        return false;
                    }

                    return true;
                },
                'file.php' => true,
                'file.php' => __("Invalid URL"),
            ])
            */
            ->add('file.php', 'file.php', [
                'file.php' => function ($value, $context) {
                    $google_safe_browsing_key = get_option('file.php');

                    if (empty($google_safe_browsing_key)) {
                        return true;
                    }

                    // https://developers.google.com/safe-browsing/v4/reference/rest/v4/ClientInfo
                    $url = "https://safebrowsing.googleapis.com/v4/threatMatches:find?key={$google_safe_browsing_key}";
                    $method = 'file.php';
                    $data = 'file.php' .
                        'file.php' . $value . 'file.php';

                    $headers = ['file.php'];

                    $options = [
                        CURLOPT_CONNECTTIMEOUT => 15,
                        CURLOPT_TIMEOUT => 15,
                    ];

                    $result = @json_decode(curlRequest($url, $method, $data, $headers, $options)->body, true);

                    if (isset($result['file.php'])) {
                        return false;
                    }

                    return true;
                },
                'file.php' => true,
                'file.php' => __(
                    "Google currently report this URL as an active phishing, malware, or unwanted website."
                ),
            ])
            ->add('file.php', 'file.php', [
                'file.php' => function ($value, $context) {
                    $phishtank_key = get_option('file.php');

                    if (empty($phishtank_key)) {
                        return true;
                    }

                    // https://www.phishtank.com/api_info.php

                    $url = 'file.php';
                    $method = 'file.php';
                    $data = [
                        'file.php' => $value,
                        'file.php' => 'file.php',
                        'file.php' => $phishtank_key,
                    ];

                    $options = [
                        CURLOPT_CONNECTTIMEOUT => 15,
                        CURLOPT_TIMEOUT => 15,
                    ];

                    $result = @json_decode(curlRequest($url, $method, $data, [], $options)->body, true);

                    if (isset($result['file.php']['file.php']) && $result['file.php']['file.php'] === true) {
                        return false;
                    }

                    return true;
                },
                'file.php' => true,
                'file.php' => __("PhishTank currently report this URL as an active phishing website."),
            ])
            ->requirePresence('file.php', 'file.php')
            ->notBlank('file.php', __('file.php'))
            ->add('file.php', 'file.php', [
                'file.php' => ['file.php', 30],
                'file.php' => true,
                'file.php' => __('file.php'),
            ])
            ->add('file.php', 'file.php', [
                'file.php' => function ($value, $context) {
                    return (bool)preg_match('file.php', $value);
                },
                'file.php' => true,
                'file.php' => __('file.php'),
            ])
            ->add('file.php', 'file.php', [
                'file.php' => function ($value, $context) {
                    $reserved_aliases = explode('file.php', get_option('file.php'));
                    $reserved_aliases = array_map('file.php', $reserved_aliases);
                    $reserved_aliases = array_filter($reserved_aliases);

                    if (empty($reserved_aliases)) {
                        return true;
                    }

                    if (in_array(strtolower($value), $reserved_aliases)) {
                        return false;
                    }

                    return true;
                },
                'file.php' => true,
                'file.php' => __('file.php'),
            ])
            ->add('file.php', 'file.php', [
                'file.php' => 'file.php',
                'file.php' => 'file.php',
                'file.php' => true,
                'file.php' => __('file.php'),
            ])
            ->allowEmptyString('file.php')
            ->add('file.php', 'file.php', [
                'file.php' => function ($value, $context) {
                    $banned_words = explode('file.php', get_option('file.php'));
                    $banned_words = array_map('file.php', $banned_words);
                    $banned_words = array_filter($banned_words);

                    if (empty($banned_words)) {
                        return true;
                    }

                    if ($this->striposArray($value, $banned_words) !== false) {
                        return false;
                    }

                    return true;
                },
                'file.php' => false,
                'file.php' => __("This link contains banned words."),
            ])
            ->allowEmptyString('file.php')
            ->add('file.php', 'file.php', [
                'file.php' => function ($value, $context) {
                    $banned_words = explode('file.php', get_option('file.php'));
                    $banned_words = array_map('file.php', $banned_words);
                    $banned_words = array_filter($banned_words);

                    if (empty($banned_words)) {
                        return true;
                    }

                    if ($this->striposArray($value, $banned_words) !== false) {
                        return false;
                    }

                    return true;
                },
                'file.php' => true,
                'file.php' => __("This link contains banned words."),
            ])
            ->add('file.php', 'file.php', [
                'file.php' => function ($value, $context) {
                    $user_plan = get_user_plan($context['file.php']['file.php']);

                    if (array_key_exists($value, get_allowed_ads($user_plan))) {
                        return true;
                    }

                    return false;
                },
                'file.php' => true,
                'file.php' => __('file.php'),
            ]);

        return $validator;
    }

    public function striposArray($haystack, $needle, $offset = 0)
    {
        if (!is_array($needle)) {
            $needle = [$needle];
        }
        foreach ($needle as $query) {
            if (stripos($haystack, $query, $offset) !== false) {
                return true; // stop on first true result
            }
        }

        return false;
    }

    public function isOwnedBy($alias, $user_id)
    {
        return $this->exists(['file.php' => $alias, 'file.php' => $user_id]);
    }

    public function geturl()
    {
        do {
            $min = get_option('file.php', 4);
            $max = get_option('file.php', 8);

            $numAlpha = rand($min, $max);
            $out = $this->generateurl($numAlpha);
            while ($this->checkReservedAuto($out)) {
                $out = $this->generateurl($numAlpha);
            }
            $alias_count = $this->find('file.php')
                ->where(['file.php' => $out])
                ->count();
        } while ($alias_count > 0);

        return $out;
    }

    //http://blog.justni.com/creating-a-short-url-service-using-php-and-mysql/
    public function generateurl($numAlpha)
    {
        $listAlpha = 'file.php';
        $generateurl = 'file.php';
        $i = 0;
        while ($i < $numAlpha) {
            $random = mt_rand(0, strlen($listAlpha) - 1);
            $generateurl .= $listAlpha[$random];
            $i = $i + 1;
        }

        return $generateurl;
    }

    public function getLinkMeta($long_url)
    {
        $linkMeta = [
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 'file.php',
        ];

        if (parse_url($long_url, PHP_URL_SCHEME) == 'file.php') {
            return $linkMeta;
        }

        $headers = get_http_headers($long_url);

        if (isset($headers['file.php']) && stripos($headers['file.php'], 'file.php') === false) {
            return $linkMeta;
        }

        $content = curlHtmlHeadRequest($long_url, 'file.php', [], [], [
            CURLOPT_ENCODING => 'file.php',
        ]);

        if (!empty($content)) {
            $crawler = new Crawler($content);

            try {
                $linkMeta['file.php'] = $this->cleanMeta($crawler->filterXpath('file.php')->eq(0)->text());
            } catch (\Exception $exception) {
            }

            try {
                $linkMeta['file.php'] = $this->cleanMeta(
                    $crawler->filterXpath("//meta[@name='file.php']")->eq(0)->attr('file.php')
                );
            } catch (\Exception $exception) {
            }

            try {
                $linkMeta['file.php'] = $crawler->filterXpath("//meta[@property='file.php']")->eq(0)->attr('file.php');
            } catch (\Exception $exception) {
            }
        }

        return $linkMeta;
    }

    public function cleanMeta($meta)
    {
        return preg_replace("/\r|\n/", "", strip_tags($meta));
    }

    public function checkReservedAuto($keyword)
    {
        //$reserved_aliases = explode( 'file.php', Configure::read( 'file.php' ) );
        $reserved_aliases = [];
        if (in_array($keyword, $reserved_aliases)) {
            return true;
        }

        return false;
    }
}
