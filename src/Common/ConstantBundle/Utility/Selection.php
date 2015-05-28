<?php
namespace Common\ConstantBundle\Utility;

class Selection
{
    /**
     * 値を返すための関数
     *
     * @param array $data 選択肢の配列
     * @param mixed $id 取得したいデータのID
     * @return mixed $idがnullの場合は選択肢の配列、$idが指定された場合はそのIDの値
     */
    protected function returnData($data, $id = null) {
        if (is_null($id)) {
            return $data;
        } else if (isset($data[$id])) {
            return $data[$id];
        } else {
            return false;
        }
    }

    /**
     * 登録区分(本登録／仮登録)
     */
    public function getRegEnabled($id = null){
        $data = array();
        $data[0] = '仮登録';
        $data[1] = '本登録';
        
        return $this->returnData($data, $id);
        
    }

    /**
     * 登録区分(本登録／仮登録)
     */
    public function getService($id = null){
        $data = array();
        $data['PUBMATCH'] = 'パブマッチ';
        $data['ATPRESS'] = '＠Press';
        $data['ATCLIPPING'] = '＠クリッピング';
        
        return $this->returnData($data, $id);
        
    }
        
    /**
     * 上場区分
     */
    public function getIsOverSea($id = null){
        $data = array();
        $data[0] = '日本';
        $data[1] = '海外';
        
        return $this->returnData($data, $id);
        
    }
    
    /**
     * 上場区分
     */
    public function getIpoKbn($id = null){
        $data = array();
        $data[0] = '未上場';
        $data[1] = '東証1部';
        $data[2] = '東証2部';
        $data[3] = '大証1部';
        $data[4] = '大証2部';
        $data[5] = '名証1部';
        $data[6] = '名証2部';
        $data[7] = '札証';
        $data[8] = '福証';
        $data[9] = 'JASDAQ';
        $data[14] = 'JASDAQスタンダード';
        $data[15] = 'JASDAQグロース';
        $data[10] = 'マザーズ';
        $data[11] = 'ヘラクレス・スタンダード';
        $data[12] = 'ヘラクレス・グロース';
        $data[13] = 'セントレックス';
        $data[98] = 'その他国内市場';
        $data[99] = '海外市場';
        $data[999] = '-';
        
        return $this->returnData($data, $id);
        
    }
    
    
    /**
     * 都道府県
     */
    public function getPrefecture($id = null) {
        $data = array();
        $data[1] = '北海道';
        $data[2] = '青森県';
        $data[3] = '岩手県';
        $data[4] = '宮城県';
        $data[5] = '秋田県';
        $data[6] = '山形県';
        $data[7] = '福島県';
        $data[8] = '茨城県';
        $data[9] = '栃木県';
        $data[10] = '群馬県';
        $data[11] = '埼玉県';
        $data[12] = '千葉県';
        $data[13] = '東京都';
        $data[14] = '神奈川県';
        $data[15] = '新潟県';
        $data[16] = '富山県';
        $data[17] = '石川県';
        $data[18] = '福井県';
        $data[19] = '山梨県';
        $data[20] = '長野県';
        $data[21] = '岐阜県';
        $data[22] = '静岡県';
        $data[23] = '愛知県';
        $data[24] = '三重県';
        $data[25] = '滋賀県';
        $data[26] = '京都府';
        $data[27] = '大阪府';
        $data[28] = '兵庫県';
        $data[29] = '奈良県';
        $data[30] = '和歌山県';
        $data[31] = '鳥取県';
        $data[32] = '島根県';
        $data[33] = '岡山県';
        $data[34] = '広島県';
        $data[35] = '山口県';
        $data[36] = '徳島県';
        $data[37] = '香川県';
        $data[38] = '愛媛県';
        $data[39] = '高知県';
        $data[40] = '福岡県';
        $data[41] = '佐賀県';
        $data[42] = '長崎県';
        $data[43] = '熊本県';
        $data[44] = '大分県';
        $data[45] = '宮崎県';
        $data[46] = '鹿児島県';
        $data[47] = '沖縄県';
        $data[48] = '海外';
        return $this->returnData($data, $id);
    }

    /**
     * 所在地区分
     */
    public function getLocation($id = null) {
        $data = array();
        $data[0] = '日本';
        $data[1] = '海外';
        return $this->returnData($data, $id);
    }

    /**
     * 上場・非上場
     */
    public function getListed($id = null) {
        $data = array();
        $data[0] = '未上場';
        $data[1] = '東証1部';
        $data[2] = '東証2部';
        $data[3] = '大証1部';
        $data[4] = '大証2部';
        $data[5] = '名証1部';
        $data[6] = '名証2部';
        $data[7] = '札証';
        $data[8] = '福証';
        $data[9] = 'JASDAQ';
        $data[10] = 'JASDAQスタンダード';
        $data[11] = 'JASDAQグロース';
        $data[12] = 'マザーズ';
        $data[13] = 'ヘラクレス・スタンダード';
        $data[14] = 'ヘラクレス・グロース';
        $data[15] = 'セントレックス';
        $data[16] = 'その他国内市場';
        $data[17] = '海外市場';
        
        return $this->returnData($data, $id);
    }
    
    /**
     * セッション区分
     */
    public function getSessionType($id = null){
        $data = array();
        $data[0] = 'PubMatch';
        $data[1] = 'Media-Item';
        return $this->returnData($data, $id);
    }

    /**
     * 上場・非上場
     */
    public function getMediaListed($id = null) {
        $data = array();
        $data[1] = '上場企業';
        $data[0] = '非上場企業';
        return $this->returnData($data, $id);
    }

    /**
     * 上場・非上場
     */
    public function getUseBillInfo($id = null) {
        $data = array();
        $data[0] = '担当者情報と同じ';
        $data[1] = '担当者情報と異なる';
        return $this->returnData($data, $id);
    }
    
    /**
     * 法人区分
     */
    public function getPersonType($id = null) {
        $data = array();
        $data[2] = '一般企業';
        $data[4] = '広告・プロモーション代理店';        
        $data[5] = 'PR・広報代理店';
        $data[3] = '官公庁・団体';
        $data[1] = 'その他';
        return $this->returnData($data, $id);
    }

    /**
     * 年
     */
    public function getBirthYear($id = null) {
        $data = array();
        
        $year = date( "Y", time());
        
        for ($i = 1800 ; $i <= $year; $i++)
        {
            $data[$i] = $i;
            
        }
        return $this->returnData($data, $id);
    }
    
    /**
     * 月
     */
    public function getMonth($id = null) {
        $data = array();
        $data[1] = '01';
        $data[2] = '02';
        $data[3] = '03';
        $data[4] = '04';
        $data[5] = '05';
        $data[6] = '06';
        $data[7] = '07';
        $data[8] = '08';
        $data[9] = '09';
        $data[10] = '10';
        $data[11] = '11';
        $data[12] = '12';
        return $this->returnData($data, $id);
    }

    public function getDays($id = null) {
        $data = array();
        $data[1] = '01';
        $data[2] = '02';
        $data[3] = '03';
        $data[4] = '04';
        $data[5] = '05';
        $data[6] = '06';
        $data[7] = '07';
        $data[8] = '08';
        $data[9] = '09';
        $data[10] = '10';
        $data[11] = '11';
        $data[12] = '12';
        $data[13] = '13';
        $data[14] = '14';
        $data[15] = '15';
        $data[16] = '16';
        $data[17] = '17';
        $data[18] = '18';
        $data[19] = '19';
        $data[20] = '20';
        $data[21] = '21';
        $data[22] = '22';
        $data[23] = '23';
        $data[24] = '24';
        $data[25] = '25';
        $data[26] = '26';
        $data[27] = '27';
        $data[28] = '28';
        $data[29] = '29';
        $data[30] = '30';
        $data[31] = '31';
        return $this->returnData($data, $id);
    }

    public function getDay($id = null) {
        $data = array();
        $data[1] = '1日';
        $data[2] = '2日';
        $data[3] = '3日';
        $data[4] = '4日';
        $data[5] = '5日';
        $data[6] = '6日';
        $data[7] = '7日';
        $data[8] = '8日';
        $data[9] = '9日';
        $data[10] = '10日';
        $data[11] = '11日';
        $data[12] = '12日';
        $data[13] = '13日';
        $data[14] = '14日';
        $data[15] = '15日';
        $data[16] = '16日';
        $data[17] = '17日';
        $data[18] = '18日';
        $data[19] = '19日';
        $data[20] = '20日';
        $data[21] = '21日';
        $data[22] = '22日';
        $data[23] = '23日';
        $data[24] = '24日';
        $data[25] = '25日';
        $data[26] = '26日';
        $data[27] = '27日';
        $data[28] = '28日';
        $data[29] = '29日';
        $data[30] = '30日';
        $data[31] = '31日';
        return $this->returnData($data, $id);
    }
    
    public function getWeeks($id = null){
        $data = array();
        $data[0] = '日';
        $data[1] = '月';
        $data[2] = '火';
        $data[3] = '水';
        $data[4] = '木';
        $data[5] = '金';
        $data[6] = '土';
        return $this->returnData($data, $id);
    }
            

    /**
     * 日
     */
    public function getDayExtended($id = null) {
        $data = array(101=>'頃',102=>'以前',103=>'上旬',104=>'中旬',105=>'下旬')+$this->getDay();
        return $this->returnData($data, $id);
    }

    public function getHour($id = null) {
        $data = array();
        $data[0] = '0';
        $data[1] = '1';
        $data[2] = '2';
        $data[3] = '3';
        $data[4] = '4';
        $data[5] = '5';
        $data[6] = '6';
        $data[7] = '7';
        $data[8] = '8';
        $data[9] = '9';
        $data[10] = '10';
        $data[11] = '11';
        $data[12] = '12';
        $data[13] = '13';
        $data[14] = '14';
        $data[15] = '15';
        $data[16] = '16';
        $data[17] = '17';
        $data[18] = '18';
        $data[19] = '19';
        $data[20] = '20';
        $data[21] = '21';
        $data[22] = '22';
        $data[23] = '23';
        return $this->returnData($data, $id);
    }
    
    public function getMinute($id = null) {
        $data = array();
        $data[0] = '00';
        $data[1] = '01';
        $data[2] = '02';
        $data[3] = '03';
        $data[4] = '04';
        $data[5] = '05';
        $data[6] = '06';
        $data[7] = '07';
        $data[8] = '08';
        $data[9] = '09';
        $data[10] = '10';
        $data[11] = '11';
        $data[12] = '12';
        $data[13] = '13';
        $data[14] = '14';
        $data[15] = '15';
        $data[16] = '16';
        $data[17] = '17';
        $data[18] = '18';
        $data[19] = '19';
        $data[20] = '20';
        $data[21] = '21';
        $data[22] = '22';
        $data[23] = '23';
        $data[24] = '24';
        $data[25] = '25';
        $data[26] = '26';
        $data[27] = '27';
        $data[28] = '28';
        $data[29] = '29';
        $data[30] = '30';
        $data[31] = '31';
        $data[32] = '32';
        $data[33] = '33';
        $data[34] = '34';
        $data[35] = '35';
        $data[36] = '36';
        $data[37] = '37';
        $data[38] = '38';
        $data[39] = '39';
        $data[40] = '40';
        $data[41] = '41';
        $data[42] = '42';
        $data[43] = '43';
        $data[44] = '44';
        $data[45] = '45';
        $data[46] = '46';
        $data[47] = '47';
        $data[48] = '48';
        $data[49] = '49';
        $data[50] = '50';
        $data[51] = '51';
        $data[52] = '52';
        $data[53] = '53';
        $data[54] = '54';
        $data[55] = '55';
        $data[56] = '56';
        $data[57] = '57';
        $data[58] = '58';
        $data[59] = '59';
        return $this->returnData($data, $id);
    }

    /**
     * 国内地域
     */
    public function getDomesticArea($id = null)
    {
        $data = array(
            '北海道' => array(1 => '北海道'),
            '東北' => array(2 => '青森',3 => '岩手',4 => '宮城',5 => '秋田',6 => '山形',7 => '福島'),
            '関東' => array(8 => '茨城',9 => '栃木',10 => '群馬',11 => '埼玉',12 => '千葉',13 => '東京' ,14 => '神奈川'),
            '中部' => array(15 => '新潟',16 => '富山',17 => '石川',18 => '福井',19 => '山梨',20 => '長野',21 => '岐阜',22 => '静岡',23 => '愛知'),
            '関西' => array(24 => '三重',25 => '滋賀',26 => '京都',27 => '大阪',28 => '兵庫',29 => '奈良',30 => '和歌山'),
            '中国' => array(31 => '鳥取',32 => '島根',33 => '岡山',34 => '広島',35 => '山口'),
            '四国' => array(36 => '徳島',37 => '香川',38 => '愛媛',39 => '高知'),
            '九州・沖縄' => array(40 => '福岡',41 => '佐賀',42 => '長崎',43 => '熊本',44 => '大分',45 => '宮崎',46 => '鹿児島',47 => '沖縄'),
        );
        return $this->returnData($data, $id);
    }

    /**
     * 海外地域
     */
     public function getOverseasArea($id = null)
     {
         $data = array(
             'アジア' => array(51 => '中国',52 => '韓国',53 =>'タイ',54 => 'インド',55 => 'シンガポール',56 => 'インドネシア', 57 => 'ベトナム', 58 => '台湾' , 59 => 'フィリピン', 60 =>'マレーシア', 300 =>'その他'),
             '北米' => array(71 => 'アメリカ',72 => 'カナダ',301 => 'その他'),
             'ヨーロッパ' => array(81 => 'イギリス',82 => 'オランダ',83 => 'スイス',84 => 'ドイツ',85 => 'フランス',86 => 'ポルトガル',87 => 'イタリア',88 => 'オーストリア', 89 => 'スウェーデン',90 =>'スペイン',91 =>'デンマーク',92 =>'ベルギー',93 =>'ロシア',302 =>'その他' ),
             'オセアニア' => array(101 => 'オーストラリア',303 => 'その他'),
             'その他' => array(110 => '中近東',120 => 'アフリカ',130 => '中南米',310 => 'その他'),
         );
         return $this->returnData($data, $id);
     }

    /**
     * なし・あり
     */
    public function getHaveOrNot($id = null) {
        $data = array();
        $data[0] = 'なし';
        $data[1] = 'あり';
        return $this->returnData($data, $id);
    }

    /**
     * 不可・可
     */
    public function getAllowOrNot($id = null) {
        $data = array();
        $data[0] = '不可';
        $data[1] = '可';
        return $this->returnData($data, $id);
    }
     
    /**
     * 会員ページお問い合わせの種類
     */
    public function getContactList() {
        $data = array();
        $data[1] = 'サービス内容について';
        $data[4] = 'サービス定期説明会お申込み';
        $data[5] = '電話によるサービス説明お申込み';
        $data[2] = 'サイトの使い方やサイトの不具合について';
        $data[3] = 'その他';
        return $this->returnData($data);
    }

    /**
     * 参加希望人数
     */
    public function getHopePerson() {
        $data = array();
        $data[1] = '1';
        $data[2] = '2';
        $data[3] = '3';

        return $this->returnData($data);
    }
    
    /**
     * 個別相談希望の有無
     */
    public function getHopeConsul() {
        $data = array();
        $data[1] = '希望する';
        $data[2] = '希望しない';
        return $this->returnData($data);
    }
    
    public function getLimit($id = null)
    {
        $data = array();
        $data['20'] = '20件';
        $data['100'] = '100件';
        $data['500'] = '500件';
        return $this->returnData($data, $id);        
    }

    /**
     * サイトの種類
     * @param type $id
     * @return type
     */
    public function getSiteType($id = null)
    {
        $data = array();
        $data[0] = 'PubMatch';
        $data[1] = 'M-Item';
        return $this->returnData($data, $id);
    }

    /**
     * 表示対象
     * @param type $id
     * @return type
     */
    public function getNewsTarget($id = null)
    {
        $data = array();
        $data[0] = '両方';
        $data[1] = 'Topページのみ';
        $data[2] = 'Myページのみ';
        return $this->returnData($data, $id);
    }
    
    /**
     * お知らせ種類
     * @param type $id
     * @return type
     */
    public function getNewsType($id = null)
    {
        $data = array();
        $data[0] = '一般';
        $data[1] = 'セミナー';
        return $this->returnData($data, $id);
    }
    
    /**
     * 公開設定
     * @param type $id
     * @return type
     */
    public function getNewsIsHide($id = null)
    {
        $data = array();
        $data[0] = '公開する';
        $data[1] = '非公開にする';
        return $this->returnData($data, $id);
    }    
    
    /**
     * メッセージ　未読／既読
     * @param type $id
     * @return type 
     */
    public function getIsRead($id = null)
    {
        $data = array();
        $data['false'] = '未読';
        $data['true'] = '既読';
        return $this->returnData($data, $id);
    }
    
    /**
     * メルマガ　受け取る／受け取らない
     * @param type $id
     * @return type 
     */
    public function getMailMagazineReceive($id = null)
    {
        $data = array();
        $data[0] = '受け取る';
        $data[1] = '受け取らない';
        return $this->returnData($data, $id);
    }
    
    public function getSpecificationType($id = null)
    {
        $data = array();
        $data[0] = '指定する';
        $data[1] = '指定しない';
        return $this->returnData($data, $id);        
    }
    
    
    public function getRank($id = null)
    {
        $data = array();
        $data[0] = '初心者';
        $data[1] = '勉強中';
        $data[2] = '普通';
        $data[3] = '上級';
        $data[4] = 'エキスパート';
        return $this->returnData($data, $id);        
    }
    
    
    public function getExperienceYear($id = null)
    {
        $data = array();
        $data[0] = '半年未満';
        $data[1] = '半年～1年';
        $data[2] = '1～3年';
        $data[3] = '3～5年';
        $data[4] = '5年以上';
        return $this->returnData($data, $id);        
    }
    
    public function getPeriod($id = null)
    {
        $data = array();
        $data[0] = '1日';
        $data[1] = '2日';
        $data[2] = '3日';
        $data[3] = '4日';
        $data[4] = '5日';
        $data[5] = '6日';
        $data[6] = '1週間';
        $data[7] = '10日';
        $data[8] = '2週間';
        $data[9] = '3週間';
        $data[10] = '1か月';
        $data[11] = '2か月';
        $data[12] = '3か月';
        $data[13] = '4か月';
        $data[14] = '5か月';
        $data[15] = '半年';
        $data[16] = '1年';
        return $this->returnData($data, $id);        
    }
    
    public function getCurrency($id = null)
    {
        $data = array();
        $data[0] = 'JPY';
        $data[1] = 'USD';
        $data[2] = 'SGD';
        $data[3] = 'CNY';
        return $this->returnData($data, $id);        
    }

    public function getBillClosingDayType($id = null)
    {
        $data = array();
        $data[5] = '5日';
        $data[10] = '10日';
        $data[15] = '15日';
        $data[20] = '20日';
        $data[25] = '25日';
        $data[30] = '月末日';
        return $this->returnData($data, $id);
    }

    public function getBillPaymentCycleType($id = null)
    {
        $data = array();
        $data[0] = '当月';
        $data[1] = '翌月';
        $data[2] = '翌々月';
        $data[3] = 'その他';
        return $this->returnData($data, $id);
    }

    public function getPaymentDayType($id = null)
    {
        $data = array();
        $data[5] = '5日';
        $data[10] = '10日';
        $data[15] = '15日';
        $data[20] = '20日';
        $data[25] = '25日';
        $data[30] = '月末日';
        return $this->returnData($data, $id);
    }

}
