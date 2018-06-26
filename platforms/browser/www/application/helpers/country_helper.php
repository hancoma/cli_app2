<?php
/**
 * Created by PhpStorm.
 * User: jihye
 * Date: 2017. 11. 27.
 * Time: PM 5:47
 */


defined('BASEPATH') OR exit('No direct script access allowed');

class Language
{
    var $COUNTRY_LIST = [
        array("name_en" => "Afghanistan", "name_ja" => "アフガニスタン", "name_ko" => "아프가니스탄", "country_code" => "AF")
        , array("name_en" => "Angola", "name_ja" => "アンゴラ", "name_ko" => "앙골라", "country_code" => "AO")
        , array("name_en" => "Albania", "name_ja" => "アルバニア", "name_ko" => "알바니아", "country_code" => "AL")
        , array("name_en" => "United Arab Emirates", "name_ja" => "アラブ首長国連邦", "name_ko" => "아랍에미리트", "country_code" => "AE")
        , array("name_en" => "Argentina", "name_ja" => "アルゼンチン", "name_ko" => "아르헨티나", "country_code" => "AR")
        , array("name_en" => "Armenia", "name_ja" => "アルメニア", "name_ko" => "아르메니아", "country_code" => "AM")
        , array("name_en" => "Antarctica", "name_ja" => "南極", "name_ko" => "남극", "country_code" => "AQ")
        , array("name_en" => "French Southern Territories", "name_ja" => "フランス領極南諸島", "name_ko" => "프랑스령 남방 및 남극", "country_code" => "TF") // 20180508수정됨
        , array("name_en" => "Australia", "name_ja" => "オーストラリア", "name_ko" => "오스트레일리아", "country_code" => "AU")
        , array("name_en" => "Austria", "name_ja" => "オーストリア", "name_ko" => "오스트리아", "country_code" => "AT")
        , array("name_en" => "Azerbaijan", "name_ja" => "アゼルバイジャン", "name_ko" => "아제르바이잔", "country_code" => "AZ")
        , array("name_en" => "Burundi", "name_ja" => "ブルンジ", "name_ko" => "부룬디", "country_code" => "BI")
        , array("name_en" => "Belgium", "name_ja" => "ベルギー", "name_ko" => "벨기에", "country_code" => "BE")
        , array("name_en" => "Benin", "name_ja" => "ベナン", "name_ko" => "베냉", "country_code" => "BJ")
        , array("name_en" => "Burkina Faso", "name_ja" => "ブルキナファソ", "name_ko" => "부르키나파소", "country_code" => "BF")
        , array("name_en" => "Bangladesh", "name_ja" => "バングラデシュ", "name_ko" => "방글라데시", "country_code" => "BD")
        , array("name_en" => "Bulgaria", "name_ja" => "ブルガリア", "name_ko" => "불가리아", "country_code" => "BG")
        , array("name_en" => "Bahamas", "name_ja" => "バハマ", "name_ko" => "바하마", "country_code" => "BS")
        , array("name_en" => "Bosnia and Herzegovina", "name_ja" => "ボスニア・ヘルツェゴビナ", "name_ko" => "보스니아 헤르체고비나", "country_code" => "BA") // 20180508수정됨
        , array("name_en" => "Belarus", "name_ja" => "ベラルーシ", "name_ko" => "벨라루스", "country_code" => "BY")
        , array("name_en" => "Belize", "name_ja" => "ベリーズ", "name_ko" => "벨리즈", "country_code" => "BZ")
        , array("name_en" => "Bolivia", "name_ja" => "ボリビア多民族国", "name_ko" => "볼리비아", "country_code" => "BO")
        , array("name_en" => "Brazil", "name_ja" => "ブラジル", "name_ko" => "브라질", "country_code" => "BR")
        , array("name_en" => "Brunei Darussalam", "name_ja" => "ブルネイ・ダルサラーム", "name_ko" => "브루나이", "country_code" => "BN")
        , array("name_en" => "Bhutan", "name_ja" => "ブータン", "name_ko" => "부탄", "country_code" => "BT")
        , array("name_en" => "Botswana", "name_ja" => "ボツワナ", "name_ko" => "보츠와나", "country_code" => "BW")
        , array("name_en" => "Central African Republic", "name_ja" => "中央アフリカ", "name_ko" => "중앙아프리카 공화국", "country_code" => "CF") // 20180508수정됨
        , array("name_en" => "Canada", "name_ja" => "カナダ", "name_ko" => "캐나다", "country_code" => "CA")
        , array("name_en" => "Switzerland", "name_ja" => "スイス", "name_ko" => "스위스", "country_code" => "CH")
        , array("name_en" => "Chile", "name_ja" => "チリ", "name_ko" => "칠레", "country_code" => "CL")
        , array("name_en" => "China", "name_ja" => "中国", "name_ko" => "중국", "country_code" => "CN")
        , array("name_en" => "Ivory Coast", "name_ja" => "コートジボワール", "name_ko" => "코트디브아루", "country_code" => "CI")
        , array("name_en" => "Cameroon", "name_ja" => "カメルーン", "name_ko" => "카메룬", "country_code" => "CM")
        , array("name_en" => "Democratic Republic of the Congo", "name_ja" => "コンゴ民主共和国", "name_ko" => "콩고 민주 공화국", "country_code" => "CD") // 20180508수정됨
        , array("name_en" => "Republic of the Congo", "name_ja" => "コンゴ", "name_ko" => "콩고 공화국", "country_code" => "CG") // 20180508수정됨
        , array("name_en" => "Colombia", "name_ja" => "コロンビア", "name_ko" => "콜롬비아", "country_code" => "CO")
        , array("name_en" => "Costa Rica", "name_ja" => "コスタリカ", "name_ko" => "코스타리카", "country_code" => "CR")
        , array("name_en" => "Cuba", "name_ja" => "キューバ", "name_ko" => "쿠바", "country_code" => "CU")
        , array("name_en" => "N. Cyprus", "name_ja" => "北キプロス", "name_ko" => "북키프로스", "country_code" => "CY")
        , array("name_en" => "Cyprus", "name_ja" => "キプロス", "name_ko" => "키프로스", "country_code" => "CY")
        , array("name_en" => "Czech Republic", "name_ja" => "チェコ", "name_ko" => "체코", "country_code" => "CZ") // 20180508수정됨
        , array("name_en" => "Germany", "name_ja" => "ドイツ", "name_ko" => "독일", "country_code" => "DE")
        , array("name_en" => "Djibouti", "name_ja" => "ジブチ", "name_ko" => "지부티", "country_code" => "DJ")
        , array("name_en" => "Denmark", "name_ja" => "デンマーク", "name_ko" => "덴마크", "country_code" => "DK")
        , array("name_en" => "Dominican Republic", "name_ja" => "ドミニカ共和国", "name_ko" => "도미니카 공화국", "country_code" => "DO")
        , array("name_en" => "Algeria", "name_ja" => "アルジェリア", "name_ko" => "알제리", "country_code" => "DZ")
        , array("name_en" => "Ecuador", "name_ja" => "エクアドル", "name_ko" => "에콰도르", "country_code" => "EC")
        , array("name_en" => "Egypt", "name_ja" => "エジプト", "name_ko" => "이집트", "country_code" => "EG")
        , array("name_en" => "Eritrea", "name_ja" => "エリトリア", "name_ko" => "에리트레아", "country_code" => "ER")
        , array("name_en" => "Spain", "name_ja" => "スペイン", "name_ko" => "스페인", "country_code" => "ES")
        , array("name_en" => "Estonia", "name_ja" => "エストニア", "name_ko" => "에스토니아", "country_code" => "EE")
        , array("name_en" => "Ethiopia", "name_ja" => "エチオピア", "name_ko" => "에티오피아", "country_code" => "ET")
        , array("name_en" => "Finland", "name_ja" => "フィンランド", "name_ko" => "핀란드", "country_code" => "FI")
        , array("name_en" => "Fiji", "name_ja" => "フィジー諸島", "name_ko" => "피지", "country_code" => "FJ")
        , array("name_en" => "Falkland Islands", "name_ja" => "フォークランド(マルビナス)", "name_ko" => "포클랜드 제도", "country_code" => "FK") // 20180508수정됨
        , array("name_en" => "France", "name_ja" => "フランス", "name_ko" => "프랑스", "country_code" => "FR")
        , array("name_en" => "Gabon", "name_ja" => "ガボン", "name_ko" => "가봉", "country_code" => "GA")
        , array("name_en" => "United Kingdom", "name_ja" => "イギリス", "name_ko" => "영국", "country_code" => "GB")
        , array("name_en" => "Georgia", "name_ja" => "グルジア", "name_ko" => "조지아", "country_code" => "GE")
        , array("name_en" => "Ghana", "name_ja" => "ガーナ", "name_ko" => "가나", "country_code" => "GH")
        , array("name_en" => "Equatorial Guinea", "name_ja" => "赤道ギニア", "name_ko" => "적도 기니", "country_code" => "GQ")
        , array("name_en" => "Gambia", "name_ja" => "ガンビア", "name_ko" => "감비아", "country_code" => "GM")
        , array("name_en" => "Guinea-bissau", "name_ja" => "ギニアビサウ", "name_ko" => "기니비사우", "country_code" => "GW")
        , array("name_en" => "Greece", "name_ja" => "ギリシャ", "name_ko" => "그리스", "country_code" => "GR")
        , array("name_en" => "Greenland", "name_ja" => "グリーンランド", "name_ko" => "그린란드", "country_code" => "GL")
        , array("name_en" => "Guatemala", "name_ja" => "グアテマラ", "name_ko" => "과테말라", "country_code" => "GT")
        , array("name_en" => "Guyana", "name_ja" => "ガイアナ", "name_ko" => "가이아나", "country_code" => "GY")
        , array("name_en" => "Honduras", "name_ja" => "ホンジュラス", "name_ko" => "온두라스", "country_code" => "HN")
        , array("name_en" => "Croatia", "name_ja" => "クロアチア", "name_ko" => "크로아티아", "country_code" => "HR")
        , array("name_en" => "Haiti", "name_ja" => "ハイチ", "name_ko" => "아이티", "country_code" => "HT")
        , array("name_en" => "Hungary", "name_ja" => "ハンガリー", "name_ko" => "헝가리", "country_code" => "HU")
        , array("name_en" => "Indonesia", "name_ja" => "インドネシア", "name_ko" => "인도네시아", "country_code" => "ID")
        , array("name_en" => "India", "name_ja" => "インド", "name_ko" => "인도", "country_code" => "IN")
        , array("name_en" => "Ireland", "name_ja" => "アイルランド", "name_ko" => "아일랜드", "country_code" => "IE")
        , array("name_en" => "Iran (ISLAMIC Republic Of)", "name_ja" => "イラン・イスラム共和国", "name_ko" => "이란", "country_code" => "IR")
        , array("name_en" => "Iraq", "name_ja" => "イラク", "name_ko" => "이라크", "country_code" => "IQ")
        , array("name_en" => "Iceland", "name_ja" => "アイスランド", "name_ko" => "아이슬란드", "country_code" => "IS")
        , array("name_en" => "Israel", "name_ja" => "イスラエル", "name_ko" => "이스라엘", "country_code" => "IL")
        , array("name_en" => "Italy", "name_ja" => "イタリア", "name_ko" => "이탈리아", "country_code" => "IT")
        , array("name_en" => "Jamaica", "name_ja" => "ジャマイカ", "name_ko" => "자메이카", "country_code" => "JM")
        , array("name_en" => "Jordan", "name_ja" => "ヨルダン・ハシミテ王国", "name_ko" => "요르단", "country_code" => "JO")
        , array("name_en" => "Japan", "name_ja" => "日本", "name_ko" => "일본", "country_code" => "JP")
        , array("name_en" => "Kazakhstan", "name_ja" => "カザフスタン", "name_ko" => "카자흐스탄", "country_code" => "KZ")
        , array("name_en" => "Kenya", "name_ja" => "ケニア", "name_ko" => "케냐", "country_code" => "KE")
        , array("name_en" => "Kyrgyzstan", "name_ja" => "キルギス", "name_ko" => "키르기스스탄", "country_code" => "KG")
        , array("name_en" => "Cambodia", "name_ja" => "カンボジア", "name_ko" => "캄보디아", "country_code" => "KH")
        , array("name_en" => "South Korea", "name_ja" => "韓国", "name_ko" => "대한민국", "country_code" => "KR")
        , array("name_en" => "Kosovo", "name_ja" => "コソボ", "name_ko" => "코소보", "country_code" => "XK")
        , array("name_en" => "Kuwait", "name_ja" => "クウェート", "name_ko" => "쿠웨이트", "country_code" => "KW")
        , array("name_en" => "Laos", "name_ja" => "ラオス", "name_ko" => "라오스", "country_code" => "LA") // 수정됨
        , array("name_en" => "Lebanon", "name_ja" => "レバノン", "name_ko" => "레바논", "country_code" => "LB")
        , array("name_en" => "Liberia", "name_ja" => "リベリア", "name_ko" => "라이베리아", "country_code" => "LR")
        , array("name_en" => "Libyan Arab Jamahiriya", "name_ja" => "リビア", "name_ko" => "리비아", "country_code" => "LY")
        , array("name_en" => "Sri Lanka", "name_ja" => "スリランカ", "name_ko" => "스리랑카", "country_code" => "LK")
        , array("name_en" => "Lesotho", "name_ja" => "レソト", "name_ko" => "레소토", "country_code" => "LS")
        , array("name_en" => "Lithuania", "name_ja" => "リトアニア", "name_ko" => "리투아니아", "country_code" => "LT")
        , array("name_en" => "Luxembourg", "name_ja" => "ルクセンブルク", "name_ko" => "룩셈부르크", "country_code" => "LU")
        , array("name_en" => "Latvia", "name_ja" => "ラトビア", "name_ko" => "라트비아", "country_code" => "LV")
        , array("name_en" => "Morocco", "name_ja" => "モロッコ", "name_ko" => "모로코", "country_code" => "MA")
        , array("name_en" => "Moldova Republic of", "name_ja" => "モルドバ", "name_ko" => "몰도바", "country_code" => "MD")
        , array("name_en" => "Madagascar", "name_ja" => "マダガスカル", "name_ko" => "마다가스카르", "country_code" => "MG")
        , array("name_en" => "Mexico", "name_ja" => "メキシコ", "name_ko" => "멕시코", "country_code" => "MX")
        , array("name_en" => "Macedonia", "name_ja" => "マケドニア旧ユーゴスラビア", "name_ko" => "마케도니아 공화국", "country_code" => "MK")
        , array("name_en" => "Mali", "name_ja" => "マリ", "name_ko" => "말리", "country_code" => "ML")
        , array("name_en" => "Myanmar", "name_ja" => "ミャンマー", "name_ko" => "미얀마", "country_code" => "MM")
        , array("name_en" => "Montenegro", "name_ja" => "モンテネグロ", "name_ko" => "몬테네그로", "country_code" => "ME")
        , array("name_en" => "Mongolia", "name_ja" => "モンゴル", "name_ko" => "몽골", "country_code" => "MN")
        , array("name_en" => "Mozambique", "name_ja" => "モザンビーク", "name_ko" => "모잠비크", "country_code" => "MZ")
        , array("name_en" => "Mauritania", "name_ja" => "モーリタニア・イスラム", "name_ko" => "모리타니", "country_code" => "MR")
        , array("name_en" => "Malawi", "name_ja" => "マラウイ", "name_ko" => "말라위", "country_code" => "MW")
        , array("name_en" => "Malaysia", "name_ja" => "マレーシア", "name_ko" => "말레이시아", "country_code" => "MY")
        , array("name_en" => "Namibia", "name_ja" => "ナミビア", "name_ko" => "나미비아", "country_code" => "NA")
        , array("name_en" => "New Caledonia", "name_ja" => "ニューカレドニア", "name_ko" => "누벨칼레도니", "country_code" => "NC")
        , array("name_en" => "Niger", "name_ja" => "ニジェール", "name_ko" => "니제르", "country_code" => "NE")
        , array("name_en" => "Nigeria", "name_ja" => "ナイジェリア", "name_ko" => "나이지리아", "country_code" => "NG")
        , array("name_en" => "Nicaragua", "name_ja" => "ニカラグア", "name_ko" => "니카라과", "country_code" => "NI")
        , array("name_en" => "Netherlands", "name_ja" => "オランダ", "name_ko" => "네덜란드", "country_code" => "NL")
        , array("name_en" => "Norway", "name_ja" => "ノルウェー", "name_ko" => "노르웨이", "country_code" => "NO")
        , array("name_en" => "Nepal", "name_ja" => "ネパール", "name_ko" => "네팔", "country_code" => "NP")
        , array("name_en" => "New Zealand", "name_ja" => "ニュージーランド", "name_ko" => "뉴질랜드", "country_code" => "NZ")
        , array("name_en" => "Oman", "name_ja" => "オマーン", "name_ko" => "오만", "country_code" => "OM")
        , array("name_en" => "Pakistan", "name_ja" => "パキスタン", "name_ko" => "파키스탄", "country_code" => "PK")
        , array("name_en" => "Panama", "name_ja" => "パナマ", "name_ko" => "파나마", "country_code" => "PA")
        , array("name_en" => "Peru", "name_ja" => "ペルー", "name_ko" => "페루", "country_code" => "PE")
        , array("name_en" => "Philippines", "name_ja" => "フィリピン", "name_ko" => "필리핀", "country_code" => "PH")
        , array("name_en" => "Papua New Guinea", "name_ja" => "パプアニューギニア", "name_ko" => "파푸아뉴기니", "country_code" => "PG")
        , array("name_en" => "Poland", "name_ja" => "ポーランド", "name_ko" => "폴란드", "country_code" => "PL")
        , array("name_en" => "Puerto Rico", "name_ja" => "プエルトリコ", "name_ko" => "푸에르토리코", "country_code" => "PR")
        , array("name_en" => "North Kore", "name_ja" => "北朝鮮", "name_ko" => "조선민주주의인민공화국", "country_code" => "KP") // 20180508수정됨
        , array("name_en" => "Portugal", "name_ja" => "ポルトガル", "name_ko" => "포르투갈", "country_code" => "PT")
        , array("name_en" => "Paraguay", "name_ja" => "パラグアイ", "name_ko" => "파라과이", "country_code" => "PY")
        , array("name_en" => "Qatar", "name_ja" => "カタール", "name_ko" => "카타르", "country_code" => "QA")
        , array("name_en" => "Romania", "name_ja" => "ルーマニア", "name_ko" => "루마니아", "country_code" => "RO")
        , array("name_en" => "Russia", "name_ja" => "ロシア", "name_ko" => "러시아", "country_code" => "RU")
        , array("name_en" => "Rwanda", "name_ja" => "ルワンダ", "name_ko" => "르완다", "country_code" => "RW")
        , array("name_en" => "Western Sahara", "name_ja" => "西サハラ", "name_ko" => "서사하라", "country_code" => "EH") // 20180508수정됨
        , array("name_en" => "Saudi Arabia", "name_ja" => "サウジアラビア", "name_ko" => "사우디아라비아", "country_code" => "SA")
        , array("name_en" => "Sudan", "name_ja" => "スーダン", "name_ko" => "수단", "country_code" => "SD")
        , array("name_en" => "South Sudan", "name_ja" => "南スーダン", "name_ko" => "남 수단", "country_code" => "SS")
        , array("name_en" => "Senegal", "name_ja" => "セネガル", "name_ko" => "세네갈", "country_code" => "SN")
        , array("name_en" => "Solomon Islands", "name_ja" => "ソロモン諸島", "name_ko" => "솔로몬 제도", "country_code" => "SB")
        , array("name_en" => "Sierra Leone", "name_ja" => "シエラレオネ", "name_ko" => "시에라리온", "country_code" => "SL")
        , array("name_en" => "El Salvador", "name_ja" => "エルサルバドル", "name_ko" => "엘살바도르", "country_code" => "SV")
        , array("name_en" => "Somaliland", "name_ja" => "ソマリランド", "name_ko" => "소말릴란드", "country_code" => "SO")
        , array("name_en" => "Somalia", "name_ja" => "ソマリア", "name_ko" => "소말리아", "country_code" => "SO")
        , array("name_en" => "Serbia", "name_ja" => "セルビア", "name_ko" => "세르비아", "country_code" => "RS")
        , array("name_en" => "Suriname_en", "name_ja" => "スリナム", "name_ko" => "수리남", "country_code" => "SR")
        , array("name_en" => "Slovakia (SLOVAK Republic)", "name_ja" => "スロバキア", "name_ko" => "슬로바키아", "country_code" => "SK")
        , array("name_en" => "Slovenia", "name_ja" => "スロベニア", "name_ko" => "슬로베니아", "country_code" => "SI")
        , array("name_en" => "Sweden", "name_ja" => "スウェーデン", "name_ko" => "스웨덴", "country_code" => "SE")
        , array("name_en" => "Swaziland", "name_ja" => "スワジランド", "name_ko" => "스와질란드", "country_code" => "SZ")
        , array("name_en" => "Syrian Arab Republic", "name_ja" => "シリア・アラブ共和国", "name_ko" => "시리아", "country_code" => "SY")
        , array("name_en" => "Chad", "name_ja" => "チャド", "name_ko" => "차드", "country_code" => "TD")
        , array("name_en" => "Togo", "name_ja" => "トーゴ", "name_ko" => "토고", "country_code" => "TG")
        , array("name_en" => "Thailand", "name_ja" => "タイ", "name_ko" => "태국", "country_code" => "TH")
        , array("name_en" => "Tajikistan", "name_ja" => "タジキスタン", "name_ko" => "타지키스탄", "country_code" => "TJ")
        , array("name_en" => "Turkmenistan", "name_ja" => "トルクメニスタン", "name_ko" => "투르크메니스탄", "country_code" => "TM")
        , array("name_en" => "East Timor", "name_ja" => "東ティモール民主共和国", "name_ko" => "동티모르", "country_code" => "TL") // 20180508수정됨
        , array("name_en" => "Trinidad and Tobago", "name_ja" => "トリニダード・トバゴ", "name_ko" => "트리니다드 토바고", "country_code" => "TT")
        , array("name_en" => "Tunisia", "name_ja" => "チュニジア", "name_ko" => "튀니지", "country_code" => "TN")
        , array("name_en" => "Turkey", "name_ja" => "トルコ", "name_ko" => "터키", "country_code" => "TR")
        , array("name_en" => "Taiwan; Republic of China (ROC)", "name_ja" => "台湾", "name_ko" => "중화민국", "country_code" => "TW")
        , array("name_en" => "Tanzania United Republic of", "name_ja" => "タンザニア", "name_ko" => "탄자니아", "country_code" => "TZ")
        , array("name_en" => "Uganda", "name_ja" => "ウガンダ", "name_ko" => "우간다", "country_code" => "UG")
        , array("name_en" => "Ukraine", "name_ja" => "ウクライナ", "name_ko" => "우크라이나", "country_code" => "UA")
        , array("name_en" => "Uruguay", "name_ja" => "ウルグアイ", "name_ko" => "우루과이", "country_code" => "UY")
        , array("name_en" => "United States", "name_ja" => "アメリカ", "name_ko" => "미국", "country_code" => "US")
        , array("name_en" => "Uzbekistan", "name_ja" => "ウズベキスタン", "name_ko" => "우즈베키스탄", "country_code" => "UZ")
        , array("name_en" => "Venezuela", "name_ja" => "ベネズエラ・ボリバル", "name_ko" => "베네수엘라", "country_code" => "VE")
        , array("name_en" => "Vietnam", "name_ja" => "ベトナム", "name_ko" => "베트남", "country_code" => "VN")
        , array("name_en" => "Vanuatu", "name_ja" => "バヌアツ", "name_ko" => "Vanuatu", "country_code" => "VU")
        , array("name_en" => "Palestinian Territory", "name_ja" => "ウェスト バンク", "name_ko" => "West Bank", "country_code" => "PS") // 20180508수정됨
        , array("name_en" => "Yemen", "name_ja" => "イエメン", "name_ko" => "예멘", "country_code" => "YE")
        , array("name_en" => "South Africa", "name_ja" => "南アフリカ", "name_ko" => "남아프리카 공화국", "country_code" => "ZA")
        , array("name_en" => "Zambia", "name_ja" => "ザンビア", "name_ko" => "잠비아", "country_code" => "ZM")
        , array("name_en" => "Zimbabwe", "name_ja" => "ジンバブエ", "name_ko" => "짐바브웨", "country_code" => "ZW")
        //20180119 추가
        , array("name_en" => "Singapore", "name_ja" => "シンガポール", "name_ko" => "싱가포르", "country_code" => "SG")
        , array("name_en" => "Hong Kong", "name_ja" => "香港", "name_ko" => "홍콩", "country_code" => "HK")
        //20180508 추가
        , array("name_en" => "Aland Islands", "name_ja" => "アランド諸島", "name_ko" => "올란드 제도", "country_code" => "AX")
        , array("name_en" => "American Samoa", "name_ja" => "アメリカンサモア", "name_ko" => "아메리칸사모아", "country_code" => "AS")
        , array("name_en" => "Andorra", "name_ja" => "アンドラ", "name_ko" => "안도라", "country_code" => "AD")
        , array("name_en" => "Anguilla", "name_ja" => "アンギラ", "name_ko" => "앵귈라", "country_code" => "AI")
        , array("name_en" => "Antigua and Barbuda", "name_ja" => "アンティグアバーブーダ", "name_ko" => "앤티가 바부다", "country_code" => "AG")
        , array("name_en" => "Aruba", "name_ja" => "アルバ", "name_ko" => "아루바", "country_code" => "AW")
        , array("name_en" => "British Indian Ocean Territory", "name_ja" => "イギリス領インド洋地域", "name_ko" => "영국령 인도양 지역", "country_code" => "IO")
        , array("name_en" => "British Virgin Islands", "name_ja" => "イギリス領バージン諸島", "name_ko" => "영국령 버진아일랜드", "country_code" => "VG")
        , array("name_en" => "Cayman Islands", "name_ja" => "ケイマン諸島", "name_ko" => "케이맨 제도", "country_code" => "KY")
        , array("name_en" => "East Timor", "name_ja" => "東ティモール", "name_ko" => "동티모르", "country_code" => "TL")
        , array("name_en" => "Faroe Islands", "name_ja" => "フェロー諸島", "name_ko" => "페로 제도", "country_code" => "FO")
        , array("name_en" => "French Polynesia", "name_ja" => "フランス領ポリネシア", "name_ko" => "프랑스령 폴리네시아", "country_code" => "PF")
        , array("name_en" => "Mayotte", "name_ja" => "マヨット", "name_ko" => "마요트", "country_code" => "YT")
        , array("name_en" => "Micronesia", "name_ja" => "ミクロネシア", "name_ko" => "미크로네시아 연방", "country_code" => "FM")
        , array("name_en" => "Monaco", "name_ja" => "モナコ", "name_ko" => "모나코", "country_code" => "MC")
        , array("name_en" => "Montserrat", "name_ja" => "モントセラト", "name_ko" => "몬트세랫", "country_code" => "MS")
        , array("name_en" => "Nauru", "name_ja" => "ナウル", "name_ko" => "나우루", "country_code" => "NR")
        , array("name_en" => "Niue", "name_ja" => "ニウエ", "name_ko" => "니우에", "country_code" => "NU")
        , array("name_en" => "Norfolk Island", "name_ja" => "ノーフォーク島", "name_ko" => "노퍽 섬", "country_code" => "NF")
        , array("name_en" => "Northern Mariana Islands", "name_ja" => "北マリアナ諸島", "name_ko" => "북마리아나 제도", "country_code" => "MP")
        , array("name_en" => "Palau", "name_ja" => "パラオ", "name_ko" => "팔라우", "country_code" => "PW")
        , array("name_en" => "Pitcairn", "name_ja" => "ピットケアンズ", "name_ko" => "핏케언 제도", "country_code" => "PN")
        , array("name_en" => "Reunion", "name_ja" => "再会", "name_ko" => "레위니옹", "country_code" => "RE")
        , array("name_en" => "Saint Barthelemy", "name_ja" => "聖バルトレミー", "name_ko" => "생바르텔레미", "country_code" => "BL")
        , array("name_en" => "Saint Helena", "name_ja" => "セントヘレナ", "name_ko" => "세인트헬레나", "country_code" => "SH")
        , array("name_en" => "Saint Kitts and Nevis", "name_ja" => "セントクリストファー・ネイビス", "name_ko" => "세인트키츠 네비스", "country_code" => "KN")
        , array("name_en" => "Saint Lucia", "name_ja" => "セントルシア", "name_ko" => "세인트루시아", "country_code" => "LC")
        , array("name_en" => "Saint Martin", "name_ja" => "サンマルタン", "name_ko" => "신트마르턴", "country_code" => "MF")
        , array("name_en" => "Saint Pierre and Miquelon", "name_ja" => "サンピエールとミケロン", "name_ko" => "생피에르 미클롱", "country_code" => "PM")
        , array("name_en" => "Saint Vincent and the Grenadines", "name_ja" => "セントビンセントとグレナディーン", "name_ko" => "세인트빈센트 그레나딘", "country_code" => "VC")
        , array("name_en" => "Samoa", "name_ja" => "サモア", "name_ko" => "사모아", "country_code" => "WS")
        , array("name_en" => "San Marino", "name_ja" => "サンマリノ", "name_ko" => "산마리노", "country_code" => "SM")
        , array("name_en" => "Sao Tome and Principe", "name_ja" => "サントメプリンシペ", "name_ko" => "상투메 프린시페", "country_code" => "ST")
        , array("name_en" => "Seychelles", "name_ja" => "セイシェル", "name_ko" => "세이셸", "country_code" => "SC")
        , array("name_en" => "Sint Maarten", "name_ja" => "シントマールテン", "name_ko" => "신트마르턴", "country_code" => "SX")
        , array("name_en" => "South Georgia and the South Sandwich Islands", "name_ja" => "サウスジョージア州とサウス・サンドイッチ諸島", "name_ko" => "사우스조지아 사우스샌드위치 제도", "country_code" => "GS")
        , array("name_en" => "Svalbard and Jan Mayen", "name_ja" => "スヴァールバルとヤン・マイエン", "name_ko" => "스발바르 얀마옌 제도", "country_code" => "SJ")
        , array("name_en" => "Tokelau", "name_ja" => "トケラウ", "name_ko" => "토켈라우", "country_code" => "TK")
        , array("name_en" => "Tonga", "name_ja" => "トンガ", "name_ko" => "통가", "country_code" => "TO")
        , array("name_en" => "Turks and Caicos Islands", "name_ja" => "タークスカイコス諸島", "name_ko" => "터크스 케이커스 제도", "country_code" => "TC")
        , array("name_en" => "Tuvalu", "name_ja" => "ツバル", "name_ko" => "투발루", "country_code" => "TV")
        , array("name_en" => "U.S. Virgin Islands", "name_ja" => "米領バージン諸島", "name_ko" => "미국령 버진아일랜드", "country_code" => "VI")
        , array("name_en" => "United States Minor Outlying Islands", "name_ja" => "アメリカ合衆国外諸島", "name_ko" => "미국령 군소 제도", "country_code" => "UM")
        , array("name_en" => "Vatican", "name_ja" => "バチカン", "name_ko" => "바티칸 시국", "country_code" => "VA")
        , array("name_en" => "Wallis and Futuna", "name_ja" => "ウォリスとフツナ", "name_ko" => "왈리스 퓌튀나", "country_code" => "WF")
        , array("name_en" => "Cape Verde", "name_ja" => "カーボベルデ", "name_ko" => "카보베르데", "country_code" => "CV")
        , array("name_en" => "Christmas Island", "name_ja" => "クリスマス島", "name_ko" => "크리스마스섬", "country_code" => "CX")
        , array("name_en" => "Cocos Islands", "name_ja" => "ココス諸島", "name_ko" => "코코스 제도", "country_code" => "CC")
        , array("name_en" => "Comoros", "name_ja" => "コモロ", "name_ko" => "코모로", "country_code" => "KM")
        , array("name_en" => "Cook Islands", "name_ja" => "クック諸島", "name_ko" => "쿡 제도", "country_code" => "CK")
        , array("name_en" => "Curacao", "name_ja" => "キュラソー", "name_ko" => "퀴라소", "country_code" => "CW")
        , array("name_en" => "Dominica", "name_ja" => "ドミニカ", "name_ko" => "도미니카 연방", "country_code" => "DM")
        , array("name_en" => "French Guiana", "name_ja" => "フランス領ギアナ", "name_ko" => "프랑스령 기아나", "country_code" => "GF")
        , array("name_en" => "Gibraltar", "name_ja" => "ジブラルタル", "name_ko" => "지브롤터", "country_code" => "GI")
        , array("name_en" => "Grenada", "name_ja" => "グレナダ", "name_ko" => "그레나다", "country_code" => "GD")
        , array("name_en" => "Guadeloupe", "name_ja" => "グアドループ", "name_ko" => "과들루프", "country_code" => "GP")
        , array("name_en" => "Guam", "name_ja" => "グアム", "name_ko" => "괌", "country_code" => "GU")
        , array("name_en" => "Guernsey", "name_ja" => "ガーンジー島", "name_ko" => "건지 섬", "country_code" => "GG")
        , array("name_en" => "Heard Island and McDonald Islands", "name_ja" => "ハード島とマクドナルド諸島", "name_ko" => "허드 맥도널드 제도", "country_code" => "HM")
        , array("name_en" => "Isle of Man", "name_ja" => "マン島", "name_ko" => "맨섬", "country_code" => "IM")
        , array("name_en" => "Jersey", "name_ja" => "ジャージー", "name_ko" => "저지 섬", "country_code" => "JE")
        , array("name_en" => "Kiribati", "name_ja" => "キリバス", "name_ko" => "키리바시", "country_code" => "KI")
        , array("name_en" => "Liechtenstein", "name_ja" => "リヒテンシュタイン", "name_ko" => "리히텐슈타인", "country_code" => "LI")
        , array("name_en" => "Bahrain", "name_ja" => "バーレーン", "name_ko" => "바레인", "country_code" => "BH")
        , array("name_en" => "Barbados", "name_ja" => "バルバドス", "name_ko" => "바베이도스", "country_code" => "BB")
        , array("name_en" => "Bermuda", "name_ja" => "バミューダ", "name_ko" => "버뮤다", "country_code" => "BM")
        , array("name_en" => "Bonaire, Saint Eustatius and Saba", "name_ja" => "ボネール、聖ユスタチオ、サバ", "name_ko" => "네덜란드령 카리브", "country_code" => "BQ")
        , array("name_en" => "Bouvet Island", "name_ja" => "ブーベ島", "name_ko" => "부베섬", "country_code" => "BV")
    ];

    var $RULE_PURPOSE_LIST = [
        array("rule_name" => "Buffer Overflow", "rule_name_en" => "Buffer Overflow", "rule_name_ja" => "バファオーバーフロー", "purpose_name_en" => "Interrupting Server", "purpose_name_ja" => "サーバ運用妨害")
        , array("rule_name" => "SQL Injection", "rule_name_en" => "SQL Injection", "rule_name_ja" => "SQLインジェクション", "purpose_name_en" => "Identity Theft", "purpose_name_ja" => "個人情報の漏えい")
        , array("rule_name" => "Cross Site Scripting", "rule_name_en" => "Cross Site Scripting", "rule_name_ja" => "クロスサイトスクリプティング", "purpose_name_en" => "Spreading Malware", "purpose_name_ja" => "悪意あるコード挿入")
        , array("rule_name" => "Stealth Commanding", "rule_name_en" => "Stealth Commanding", "rule_name_ja" => "ステルス・コマンディング", "purpose_name_en" => "Spreading Malware", "purpose_name_ja" => "悪意あるコード挿入")
        , array("rule_name" => "Error Handling", "rule_name_en" => "Error Handling", "rule_name_ja" => "エラーハンドリング", "purpose_name_en" => "Vulnerability Scan", "purpose_name_ja" => "脆弱性スキャン")
        , array("rule_name" => "Directory Listing", "rule_name_en" => "Directory Listing", "rule_name_ja" => "ディレクトリリスト", "purpose_name_en" => "Vulnerability Scan", "purpose_name_ja" => "脆弱性スキャン")
        , array("rule_name" => "Request Header Filtering", "rule_name_en" => "Request Header Filtering", "rule_name_ja" => "リクエストヘッダフィルタリング", "purpose_name_en" => "Vulnerability Scan", "purpose_name_ja" => "脆弱性スキャン")
        , array("rule_name" => "Directory Traversal", "rule_name_en" => "Directory Traversal", "rule_name_ja" => "ディレクトリトラバーサル", "purpose_name_en" => "Identity Theft", "purpose_name_ja" => "個人情報の漏えい")
        , array("rule_name" => "Request Method Filtering", "rule_name_en" => "Request Method Filtering", "rule_name_ja" => "リクエストメソッドフィルタリング", "purpose_name_en" => "Interrupting Server", "purpose_name_ja" => "サーバ運用妨害")
        , array("rule_name" => "Extension Filtering", "rule_name_en" => "Extension Filtering", "rule_name_ja" => "エクステンションフィルタリング", "purpose_name_en" => "Identity Theft", "purpose_name_ja" => "個人情報の漏えい")
        , array("rule_name" => "Invalid URL", "rule_name_en" => "Invalid URL", "rule_name_ja" => "無効なURL", "purpose_name_en" => "Vulnerability Scan", "purpose_name_ja" => "脆弱性スキャン")
        , array("rule_name" => "Response Header Filtering", "rule_name_en" => "Response Header Filtering", "rule_name_ja" => "レスポンスヘッダフィルタリング", "purpose_name_en" => "Vulnerability Scan", "purpose_name_ja" => "脆弱性スキャン")
        , array("rule_name" => "Privacy Output Filtering", "rule_name_en" => "Privacy Output Filtering", "rule_name_ja" => "プライバシーアウトプットフィルタリング", "purpose_name_en" => "Identity Theft", "purpose_name_ja" => "個人情報の漏えい")
        , array("rule_name" => "User Defined Pattern", "rule_name_en" => "User Defined Pattern", "rule_name_ja" => "ユーザー定義パターン", "purpose_name_en" => "ETC", "purpose_name_ja" => "サーバ運用妨害")
        , array("rule_name" => "Invalid HTTP", "rule_name_en" => "Invalid HTTP", "rule_name_ja" => "不正なHTTP", "purpose_name_en" => "Vulnerability Scan", "purpose_name_ja" => "脆弱性スキャン")
        , array("rule_name" => "Include Injection", "rule_name_en" => "Include Injection", "rule_name_ja" => "インクルードインジェクション", "purpose_name_en" => "Falsifying Websites", "purpose_name_ja" => "Webサイトの変造/偽造")
        , array("rule_name" => "File Upload", "rule_name_en" => "File Upload", "rule_name_ja" => "ファイルアップロード", "purpose_name_en" => "Falsifying Websites", "purpose_name_ja" => "Webサイトの変造/偽造")
        //,array("rule_name" => "Access Control",             "rule_name_en" => "Access Control",             "rule_name_ja" => "",                     "purpose_name_en" => "Interrupting Server", "purpose_name_ja" => "サーバ運用妨害")
        //,array("rule_name" => "HTTP DoS",                   "rule_name_en" => "HTTP DoS",                   "rule_name_ja" => "",                     "purpose_name_en" => "Interrupting Server", "purpose_name_ja" => "サーバ運用妨害")
        //,array("rule_name" => "IP Filtering",               "rule_name_en" => "IP Filtering",               "rule_name_ja" => "",                     "purpose_name_en" => "Interrupting Server", "purpose_name_ja" => "サーバ運用妨害")
        //,array("rule_name" => "Parameter Tampering",        "rule_name_en" => "Parameter Tampering",        "rule_name_ja" => "",                     "purpose_name_en" => "Monetary Loss", "purpose_name_ja" => "金銭的損害")
        //,array("rule_name" => "Cookie Poisoning",           "rule_name_en" => "Cookie Poisoning",           "rule_name_ja" => "",                     "purpose_name_en" => "Monetary Loss", "purpose_name_ja" => "金銭的損害")
        //,array("rule_name" => "Privacy File Filtering",     "rule_name_en" => "Privacy File Filtering",     "rule_name_ja" => "",                     "purpose_name_en" => "Identity Theft", "purpose_name_ja" => "個人情報の漏えい")
        //,array("rule_name" => "Privacy Input Filtering",    "rule_name_en" => "Privacy Input Filtering",    "rule_name_ja" => "",                     "purpose_name_en" => "Identity Theft", "purpose_name_ja" => "個人情報の漏えい")
        //,array("rule_name" => "Suspicious Access",          "rule_name_en" => "Suspicious Access",          "rule_name_ja" => "",                     "purpose_name_en" => "Interrupting Server", "purpose_name_ja" => "サーバ運用妨害")
        //,array("rule_name" => "URL Access Control",         "rule_name_en" => "URL Access Control",         "rule_name_ja" => "",                     "purpose_name_en" => "Identity Theft", "purpose_name_ja" => "個人情報の漏えい")
        //,array("rule_name" => "Website Defacement",         "rule_name_en" => "Website Defacement",         "rule_name_ja" => "",                     "purpose_name_en" => "Falsifying Websites", "purpose_name_ja" => "Webサイトの変造/偽造")
        //,array("rule_name" => "Input Content Filtering",    "rule_name_en" => "Input Content Filtering",    "rule_name_ja" => "",                     "purpose_name_en" => "Monetary Loss", "purpose_name_ja" => "金銭的損害")
        //,array("rule_name" => "Parameter Encryption",       "rule_name_en" => "Parameter Encryption",       "rule_name_ja" => "その他",                                   "purpose_name_en" => "ETC", "purpose_name_ja" => "その他")

    ];

    var $RULE_LIST = [
        array("rule_name_en" => "Buffer Overflow", "rule_name_ja" => "バファオーバーフロー")

    ];

    var $PURPOSE_LIST = [
        array("purpose_name_en" => "Interrupting Server", "purpose_name_ja" => "サーバ運用妨害")

    ];
}

/**
 * 코드로 국가명 가져오기
 * @param $code
 * @param string $lang
 * @return string
 */
function language_country_to_code($code, $lang = 'en')
{
    $lg = new Language;
    if (empty($code) || $code == '--') {
        return ($lang == 'ja') ? '不明' : 'Unknown';
    }

    $key = array_search($code, array_column($lg->COUNTRY_LIST, 'country_code'));

    $val = $lg->COUNTRY_LIST[$key]['name_' . $lang];
    if ($key == null || empty($val)) {
        return ($lang == 'ja') ? '不明' : 'Unknown';
    }
    return $val;

}

/**
 * 국가명으로 코드 가져오기
 * @param $country_name
 * @param string $lang
 * @return string
 */
function language_code_to_country($country_name, $lang = 'en')
{
    $lg = new Language;
    if (empty($country_name)) {
        return 'ZZ';
    }

    $key = array_search($country_name, array_column($lg->COUNTRY_LIST, 'name_' . $lang));

    $val = $lg->COUNTRY_LIST[$key]['country_code'];
    if ($key == null || empty($val)) {
        return 'ZZ';
    }

    return $val;

}


/**
 * 룰에 따라 목적을 가져온다
 * @param $rule_name
 * @param string $lang
 * @return string
 */
function language_purpose_to_rule($rule_name, $lang = 'en')
{
    $lg = new Language;
    if (empty($rule_name)) {
        return ($lang == 'ja') ? 'その他' : 'ETC';
    }

    $key = array_search($rule_name, array_column($lg->RULE_PURPOSE_LIST, 'rule_name'));

    $val = $lg->RULE_PURPOSE_LIST[$key]['purpose_name_' . $lang];
    if ($key == null || empty($val)) {
        return ($lang == 'ja') ? 'その他' : 'ETC';
    }
    return $val;

}

/**
 * 목적에 따라 룰명 가져온다
 * @param $purpose_name
 * @param string $lang
 * @return string
 */
function language_rule_to_purpose($purpose_name, $lang = 'en')
{
    $lg = new Language;
    if (empty($purpose_name)) {
        return ($lang == 'ja') ? 'その他' : 'ETC';
    }

    $key = array_search($purpose_name, array_column($lg->RULE_PURPOSE_LIST, 'purpose_name_' . $lang));

    $val = $lg->RULE_PURPOSE_LIST[$key]['rule_name_' . $lang];
    if ($key == null || empty($val)) {
        return ($lang == 'ja') ? 'その他' : 'ETC';
    }
    return $val;

}


/**
 * 언어에 따라 룰명을 가져온다
 * @param $rule_name
 * @param bool $origin true이면 영문그대로의 룰명을 리턴
 * @param string $lang
 * @return string
 */
function language_rule($rule_name, $origin = true, $lang = 'en')
{
    $lg = new Language;
    if (empty($rule_name)) {
        return ($lang == 'ja') ? 'その他' : 'ETC';
    }

    $key = array_search($rule_name, array_column($lg->RULE_PURPOSE_LIST, 'rule_name'));
    if ($origin)
        $val = $lg->RULE_PURPOSE_LIST[$key]['rule_name'];
    else
        $val = $lg->RULE_PURPOSE_LIST[$key]['rule_name_' . $lang];

    if ($key == null || empty($val)) {
        return ($lang == 'ja') ? 'その他' : 'ETC';
    }
    return $val;

}

