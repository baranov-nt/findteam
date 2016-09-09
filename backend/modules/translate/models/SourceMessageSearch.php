<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 27.04.2016
 * Time: 14:34
 */

namespace backend\modules\translate\models;

use yii\data\ActiveDataProvider;
use yii\console\Exception;
use yii\helpers\Console;
use yii\helpers\FileHelper;
use yii\helpers\Json;

class SourceMessageSearch extends SourceMessage
{
    /**
     * @var SourceMessageSearch
     */
    protected static $_instance = null;

    /**
     * @var array
     */
    protected $locations = [];

    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var string
     */
    public $translation;

    /**
     * @return SourceMessageSearch
     */
    public static function getInstance()
    {
        if ( null === self::$_instance )
            self::$_instance = new self();

        return self::$_instance;
    }

    public function init()
    {
        if (!\Yii::$app->has('i18n')) {
            throw new Exception('The i18n component does not exist');
        }

        $i18n =\Yii::$app->i18n;
        $this->config = [
            'languages'             => $i18n->languages,
            'sourcePath'            => (is_string($i18n->sourcePath) ? [$i18n->sourcePath] : $i18n->sourcePath),
            'translator'            => $i18n->translator,
            'sort'                  => $i18n->sort,
            'removeUnused'          => $i18n->removeUnused,
            'only'                  => $i18n->only,
            'except'                => $i18n->except,
            'format'                => $i18n->format,
            'db'                    => $i18n->db,
            'messagePath'           => $i18n->messagePath,
            'overwrite'             => $i18n->overwrite,
            'catalog'               => $i18n->catalog,
            'messageTable'          => $i18n->messageTable,
            'sourceMessageTable'    => $i18n->sourceMessageTable,
        ];
    }

    public function rules()
    {
        return [
            ['category', 'safe'],
            ['message', 'safe'],
            ['translation', 'safe'],

        ];
    }

    /**
     * @param array|null $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = SourceMessage::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ],
        ]);

        // check and populate params
        if (!($this->load($params) && $this->validate())) {
            $query->joinWith(['messages']);
            return $dataProvider;
        }

        // search with related table
        // @see http://www.yiiframework.com/wiki/621/filter-sort-by-calculated-related-fields-in-gridview-yii-2-0/
        if ( !empty($this->translation) ) {
            $query->joinWith(['messages' => function ($q) {
                $q->where(['like', Message::tableName() . '.translation', $this->translation]);
            }]);
        }

        $query
            ->andFilterWhere(['like', 'category', $this->category])
            ->andFilterWhere(['like', 'message', $this->message])
        ;

        return $dataProvider;
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id'            => 'ID',
            'category'      => \Yii::t('app', 'Категория'),
            'message'       => \Yii::t('app', 'Сообщение'),
            'location'      => \Yii::t('app', 'Путь'),
            'translation'   => \Yii::t('app', 'Перевод')
        ];
    }

    /**
     * Extracts messages to be translated from source code.
     *
     * This command will search through source code files and extract
     * messages that need to be translated in different languages.
     *
     * @throws Exception on failure.
     * @return array
     */
    public function extract()
    {
        if (!isset($this->config['sourcePath'], $this->config['languages'])) {
            throw new Exception('The configuration must specify "sourcePath" and "languages".');
        }

        foreach ($this->config['sourcePath'] as $sourcePath) {
            if (!is_dir($sourcePath)) {
                throw new Exception("The source path {$sourcePath} is not a valid directory.");
            }
        }

        if (empty($this->config['format']) || !in_array($this->config['format'], ['php', 'po', 'db'])) {
            throw new Exception('Format should be either "php", "po" or "db".');
        }
        if (in_array($this->config['format'], ['php', 'po'])) {
            if (!isset($this->config['messagePath'])) {
                throw new Exception('The configuration file must specify "messagePath".');
            } elseif (!is_dir($this->config['messagePath'])) {
                throw new Exception("The message path {$this->config['messagePath']} is not a valid directory.");
            }
        }
        if (empty($this->config['languages'])) {
            throw new Exception("Languages cannot be empty.");
        }

        $files = [];
        foreach ( $this->config['sourcePath'] as $sourcePath ) {
            $files = array_merge(
                array_values($files),
                array_values(FileHelper::findFiles(realpath($sourcePath), $this->config))
            );
        }

        $messages = [];
        foreach ($files as $file) {
            $messages = array_merge_recursive($messages, $this->extractMessages($file, $this->config['translator']));
        }

        $db = \Yii::$app->get(isset($this->config['db']) ? $this->config['db'] : 'db');
        if (!$db instanceof \yii\db\Connection) {
            throw new Exception('The "db" option must refer to a valid database application component.');
        }
        $sourceMessageTable = isset($this->config['sourceMessageTable']) ? $this->config['sourceMessageTable'] : '{{%source_message}}';
        $messageTable = isset($this->config['messageTable']) ? $this->config['messageTable'] : '{{%message}}';
        return $this->saveMessagesToDb(
            $messages,
            $db,
            $sourceMessageTable,
            $messageTable,
            $this->config['removeUnused'],
            $this->config['languages']
        );
    }

    /**
     * Saves messages to database
     *
     * @param array $messages
     * @param \yii\db\Connection $db
     * @param string $sourceMessageTable
     * @param string $messageTable
     * @param boolean $removeUnused
     * @param array $languages
     */
    public function saveMessagesToDb($messages, $db, $sourceMessageTable, $messageTable, $removeUnused, $languages)
    {
        $q = new \yii\db\Query;
        $current = [];

        foreach ($q->select(['id', 'category', 'message'])->from($sourceMessageTable)->all() as $row) {
            $current[$row['category']][$row['id']] = $row['message'];
        }

        /* Запись местоположения во все пустые ячейки */
        $newMessages = 0;
        $msgHash = md5(time());

        foreach ($messages as $category => $msgs) {
            if ($category != 'yii') {
                foreach ($msgs as $m) {
                    $modelSourceMessage = ($modelSourceMessage = SourceMessage::find()
                        ->where([
                            'message' => $m,
                        ])
                        ->one()) ? $modelSourceMessage : new SourceMessage();
                    $modelSourceMessage->category = $category;
                    $modelSourceMessage->message = $m;
                    $modelSourceMessage->hash = $msgHash;
                    $modelSourceMessage->location = $this->extractLocations($category, $m);
                    $modelSourceMessage->save();
                    $newMessages++;
                }
            }
        }

        $modelSourceMessage = SourceMessage::find()
            ->where(['!=', 'hash', $msgHash])
            ->andWhere(['!=', 'category', 'yii'])
            ->count();

        SourceMessage::deleteAll('hash != :hash AND category != :category', [':hash' => $msgHash, ':category' => 'yii']);

        return ['new' => $newMessages, 'deleted' => $modelSourceMessage];
    }

    /**
     * @param string $category
     * @param string $message
     * @return string
     */
    protected function extractLocations($category, $message)
    {
        $output  = [];
        $msgHash = md5($message);

        foreach ( $this->locations[$category] as $location ) {
            if ( isset($location[$msgHash]) ) {
                $output[] = $location[$msgHash];
            }
        }

        return Json::encode($output);
    }

    /**
     * Extracts messages from a file
     *
     * @param string $fileName name of the file to extract messages from
     * @param string $translator name of the function used to translate messages
     * @return array
     */
    protected function extractMessages($fileName, $translator)
    {
        $coloredFileName = Console::ansiFormat($fileName, [Console::FG_CYAN]);
        $this->stdout("Extracting messages from $coloredFileName...\n");

        $subject  = file_get_contents($fileName);
        $messages = [];
        foreach ((array)$translator as $currentTranslator) {
            $translatorTokens = token_get_all('<?php ' . $currentTranslator);
            array_shift($translatorTokens);

            $translatorTokensCount = count($translatorTokens);
            $matchedTokensCount = 0;
            $buffer = [];

            $tokens = token_get_all($subject);
            foreach ($tokens as $token) {
                // finding out translator call
                if ($matchedTokensCount < $translatorTokensCount) {
                    if ($this->tokensEqual($token, $translatorTokens[$matchedTokensCount])) {
                        $matchedTokensCount++;
                    } else {
                        $matchedTokensCount = 0;
                    }
                } elseif ($matchedTokensCount === $translatorTokensCount) {
                    // translator found
                    // end of translator call or end of something that we can't extract
                    if ($this->tokensEqual(')', $token)) {
                        if (isset($buffer[0][0], $buffer[1], $buffer[2][0]) && $buffer[0][0] === T_CONSTANT_ENCAPSED_STRING && $buffer[1] === ',' && $buffer[2][0] === T_CONSTANT_ENCAPSED_STRING) {
                            // is valid call we can extract

                            $category = stripcslashes($buffer[0][1]);
                            $category = mb_substr($category, 1, mb_strlen($category) - 2);

                            $message = stripcslashes($buffer[2][1]);
                            $message = mb_substr($message, 1, mb_strlen($message) - 2);

                            $messages[$category][] = $message;
                            foreach ($this->config['sourcePath'] as $sourcePath) {
                                $location = str_replace(realpath($sourcePath), '', $fileName);
                                if ( $location !== $fileName ) {
                                    $parts = explode('/', $sourcePath);
                                    $key   = count($parts) - 1;
                                    $this->locations[$category][] = [md5($message) => $parts[$key] . $location];
                                }
                            }
                        } else {
                            // invalid call or dynamic call we can't extract
                            $line = Console::ansiFormat($this->getLine($buffer), [Console::FG_CYAN]);
                            $skipping = Console::ansiFormat('Skipping line', [Console::FG_YELLOW]);
                            $this->stdout("$skipping $line. Make sure both category and message are static strings.\n");
                        }

                        // prepare for the next match
                        $matchedTokensCount = 0;
                        $buffer = [];
                    } elseif ($token !== '(' && isset($token[0]) && !in_array($token[0], [T_WHITESPACE, T_COMMENT])) {
                        // ignore comments, whitespaces and beginning of function call
                        $buffer[] = $token;
                    }
                }
            }
        }

        return $messages;
    }

    /**
     * Finds out if two PHP tokens are equal
     *
     * @param array|string $a
     * @param array|string $b
     * @return boolean
     * @since 2.0.1
     */
    protected function tokensEqual($a, $b)
    {
        if (is_string($a) && is_string($b)) {
            return $a === $b;
        } elseif (isset($a[0], $a[1], $b[0], $b[1])) {
            return $a[0] === $b[0] && $a[1] == $b[1];
        }

        return false;
    }

    /**
     * Finds out a line of the first non-char PHP token found
     *
     * @param array $tokens
     * @return int|string
     * @since 2.0.1
     */
    protected function getLine($tokens)
    {
        foreach ($tokens as $token) {
            if (isset($token[2])) {
                return $token[2];
            }
        }

        return 'unknown';
    }

    /**
     * Prints a string to STDOUT
     *
     * You may optionally format the string with ANSI codes by
     * passing additional parameters using the constants defined in [[\yii\helpers\Console]].
     *
     * Example:
     *
     * ~~~
     * $this->stdout('This will be red and underlined.', Console::FG_RED, Console::UNDERLINE);
     * ~~~
     *
     * @param string $string the string to print
     * @return int|boolean Number of bytes printed or false on error
     */
    public function stdout($string)
    {
        if ( \Yii::$app->id != 'app-console' )
            return false;

        if ($this->isColorEnabled()) {
            $args = func_get_args();
            array_shift($args);
            $string = Console::ansiFormat($string, $args);
        }

        return Console::stdout($string);
    }

    /**
     * Returns a value indicating whether ANSI color is enabled.
     *
     * ANSI color is enabled only if [[color]] is set true or is not set
     * and the terminal supports ANSI color.
     *
     * @param resource $stream the stream to check.
     * @return boolean Whether to enable ANSI style in output.
     */
    public function isColorEnabled($stream = \STDOUT)
    {
        return $this->color === null ? Console::streamSupportsAnsiColors($stream) : $this->color;
    }
}