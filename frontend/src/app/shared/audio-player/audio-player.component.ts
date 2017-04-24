import {Component, OnInit, NgZone} from '@angular/core';
import {AudioRecord} from '../../interfases/audio-record';
import {SharedService} from '../shared.service';
import * as _ from 'lodash';
declare const WaveSurfer: any;


const peaks = [0.013611255213618279,-0.00634765625,0.01745658740401268,-0.031005859375,0.0238349549472332,-0.0263671875,0.024994660168886185,-0.029998779296875,0.021912289783358574,-0.048004150390625,0.03805658221244812,-0.045654296875,0.04910428076982498,-0.032318115234375,0.07425153255462646,-0.05670166015625,0.06207464635372162,-0.05145263671875,0.0716879814863205,-0.031280517578125,0.04040650650858879,-0.032135009765625,0.03515732288360596,-0.04632568359375,0.0552995391190052,-0.036163330078125,0.04858546704053879,-0.053253173828125,0.31995606422424316,-0.230438232421875,0.31318095326423645,-0.27166748046875,0.3720816671848297,-0.469390869140625,0.33573412895202637,-0.238067626953125,0.365550696849823,-0.19110107421875,0.5173802971839905,-0.237060546875,0.11618396639823914,-0.250274658203125,0.1977599412202835,-0.349395751953125,0.45527511835098267,-0.4208984375,0.27491074800491333,-0.272491455078125,0.635364830493927,-0.347015380859375,0.221533864736557,-0.132049560546875,0.20853297412395477,-0.429840087890625,0.2222052663564682,-0.370147705078125,0.21234779059886932,-0.22149658203125,0.39481794834136963,-0.264404296875,0.21811579167842865,-0.50762939453125,0.49555954337120056,-0.310791015625,0.4452345371246338,-0.252899169921875,0.34394359588623047,-0.042724609375,0.28150272369384766,-0.217010498046875,0.41825616359710693,-0.27703857421875,0.26483961939811707,-0.23138427734375,0.3337504267692566,-0.15032958984375,0.4853968918323517,-0.320648193359375,0.41007721424102783,-0.362091064453125,0.3747673034667969,-0.36749267578125,0.19257178902626038,-0.271026611328125,0.25681325793266296,-0.431976318359375,0.34736168384552,-0.44219970703125,0.4055604636669159,-0.171112060546875,0.2951139807701111,-0.2113037109375,0.28165531158447266,-0.284393310546875,0.2068849802017212,-0.336395263671875,0.383068323135376,-0.383087158203125,0.22760704159736633,-0.326446533203125,0.4865260720252991,-0.354888916015625,0.44053468108177185,-0.34674072265625,0.33884701132774353,-0.295654296875,0.3275856673717499,-0.298919677734375,0.3027436137199402,-0.35015869140625,0.1580248475074768,-0.5345458984375,0.2785119116306305,-0.3350830078125,0.3972289264202118,-0.3651123046875,0.18713949620723724,-0.36468505859375,0.3327127993106842,-0.154296875,0.19180883467197418,-0.21551513671875,0.286843478679657,-0.27252197265625,0.15585802495479584,-0.27642822265625,0.40849024057388306,-0.470977783203125,0.39963987469673157,-0.5377197265625,0.3587450683116913,-0.156158447265625,0.27546006441116333,-0.20556640625,0.11615344882011414,-0.161468505859375,0.26584672927856445,-0.327423095703125,0.29139071702957153,-0.11376953125,0.26709800958633423,-0.344390869140625,0.3030487895011902,-0.275848388671875,0.32145145535469055,-0.291168212890625,0.37656790018081665,-0.39178466796875,0.25659963488578796,-0.299560546875,0.25672170519828796,-0.211761474609375,0.2923062741756439,-0.270904541015625,0.2061220109462738,-0.227325439453125,0.2146061658859253,-0.252288818359375,0.35294654965400696,-0.166351318359375,0.16223639249801636,-0.36322021484375,0.2466505914926529,-0.27557373046875,0.20911282300949097,-0.299041748046875,0.19522690773010254,-0.29522705078125,0.3891414999961853,-0.26190185546875,0.3485824167728424,-0.353240966796875,0.3586840331554413,-0.275970458984375,0.33777886629104614,-0.3475341796875,0.12268440425395966,-0.178070068359375,0.2296822965145111,-0.291168212890625,0.16492202877998352,-0.1976318359375,0.32789087295532227,-0.438995361328125,0.35181736946105957,-0.461456298828125,0.3509933650493622,-0.3387451171875,0.19327372312545776,-0.476226806640625,0.34415721893310547,-0.398651123046875,0.32999664545059204,-0.32501220703125,0.5367595553398132,-0.212188720703125,0.41062653064727783,-0.3995361328125,0.3647266924381256,-0.278961181640625,0.4522232711315155,-0.38836669921875,0.16180913150310516,-0.450164794921875,0.3750114440917969,-0.257476806640625,0.09189122915267944,-0.279541015625,0.1825312077999115,-0.213104248046875,0.2061220109462738,-0.289337158203125,0.3786431550979614,-0.326995849609375,0.35343486070632935,-0.29400634765625,0.4294869899749756,-0.36383056640625,0.40177616477012634,-0.3743896484375,0.20062868297100067,-0.34991455078125,0.16525772213935852,-0.245941162109375,0.3632923364639282,-0.12554931640625,0.5165867805480957,-0.353485107421875,0.41660818457603455,-0.346160888671875,0.42301705479621887,-0.315155029296875,0.3791619539260864,-0.291748046875,0.110812708735466,-0.1314697265625,0.3728751540184021,-0.238555908203125,0.2935880720615387,-0.403106689453125,0.5138096213340759,-0.369842529296875,0.21829889714717865,-0.306365966796875,0.3809015154838562,-0.19378662109375,0.26541948318481445,-0.218231201171875,0.44782862067222595,-0.158172607421875,0.4602191150188446,-0.223876953125,0.31434065103530884,-0.464813232421875,0.7188024520874023,-0.340484619140625,0.40140995383262634,-0.295684814453125,0.2459486722946167,-0.261871337890625,0.10425122827291489,-0.490478515625,0.5779595375061035,-0.17926025390625,0.4827112555503845,-0.325958251953125,0.4036683142185211,-0.353607177734375,0.599078357219696,-0.301849365234375,0.3743705451488495,-0.28875732421875,0.6387218832969666,-0.31365966796875,0.5435346364974976,-0.27587890625,0.36118656396865845,-0.378387451171875,0.32004761695861816,-0.355255126953125,0.30631428956985474,-0.37530517578125,0.33835870027542114,-0.26922607421875,0.26779991388320923,-0.157073974609375,0.31446272134780884,-0.236297607421875,0.3151036202907562,-0.292633056640625,0.21195104718208313,-0.6019287109375,0.3795281946659088,-0.262176513671875,0.3091525137424469,-0.231658935546875,0.6402173042297363,-0.291656494140625,0.35923337936401367,-0.235076904296875,0.36130863428115845,-0.25982666015625,0.3708609342575073,-0.243560791015625,0.29825738072395325,-0.27032470703125,0.16452528536319733,-0.13275146484375,0.2797631621360779,-0.213104248046875,0.21655935049057007,-0.322906494140625,0.5473494529724121,-0.191436767578125,0.574327826499939,-0.363800048828125,0.299905389547348,-0.2574462890625,0.843745231628418,-0.14715576171875,0.3635059595108032,-0.33721923828125,0.8068788647651672,-0.491607666015625,0.4848475456237793,-0.771453857421875,0.22003845870494843,-0.34539794921875,0.6779991984367371,-0.474700927734375,0.6205023527145386,-0.726226806640625,0.6464125514030457,-0.757843017578125,0.6219061613082886,-0.73406982421875,0.6047853231430054,-0.503204345703125,0.8961760401725769,-0.73828125,0.1274452954530716,-0.524993896484375,0.7075105905532837,-0.483734130859375,0.4684286117553711,-0.658477783203125,0.431134968996048,-0.5626220703125,0.8017517328262329,-0.422027587890625,0.5258949398994446,-0.885894775390625,0.8352611064910889,-0.273284912109375,0.538773775100708,-0.486175537109375,0.5807672142982483,-0.517730712890625,0.7365642189979553,-0.50384521484375,0.7214880585670471,-0.20587158203125,0.5789971351623535,-0.39532470703125,0.747184693813324,-0.512603759765625,0.5710928440093994,-0.581298828125,0.38541826605796814,-0.5816650390625,0.5060884356498718,-0.626861572265625,0.7432478070259094,-0.857666015625,0.7286294102668762,-0.45318603515625,0.6136051416397095,-0.46014404296875,0.3424482047557831,-0.648681640625,0.4215521812438965,-0.622772216796875,0.5681936144828796,-0.82196044921875,0.9499801397323608,-0.55853271484375,0.48954740166664124,-0.4932861328125,0.7992797493934631,-0.83331298828125,0.17908261716365814,-0.799896240234375,0.4597613513469696,-0.70086669921875,0.43403422832489014,-0.5950927734375,0.9264808893203735,-0.59075927734375,0.8398083448410034,-0.388671875,0.5644398331642151,-0.5897216796875,0.574236273765564,-0.459869384765625,0.43189793825149536,-0.4288330078125,0.5917844176292419,-0.547515869140625,0.773735761642456,-0.467559814453125,0.36130863428115845,-0.28216552734375,0.7672048211097717,-0.784454345703125,0.3802300989627838,-0.493560791015625,0.460463285446167,-0.598052978515625,0.8740501403808594,-0.801483154296875,0.9484237432479858,-0.661163330078125,0.3820306956768036,-0.387847900390625,0.8565630316734314,-0.37872314453125,0.6278572678565979,-0.216705322265625,0.8134708404541016,-0.294647216796875,0.46308785676956177,-0.443511962890625,0.9159215092658997,-0.288116455078125,1,-0.608642578125,0.5018768906593323,-0.453155517578125,0.6052430868148804,-0.673828125,0.8575701117515564,-0.771453857421875,0.5158543586730957,-0.7247314453125,0.6487929821014404,-0.605255126953125,0.773857831954956,-0.892913818359375,0.6667378544807434,-0.6744384765625,0.5271157026290894,-0.538909912109375,0.48835718631744385,-1,0.8341013789176941,-0.41217041015625,0.3570360541343689,-0.7789306640625,0.5778374671936035,-0.367919921875,0.6732688546180725,-0.505645751953125,0.6966460347175598,-0.5390625,0.5290688872337341,-0.813385009765625,0.43986326456069946,-0.54937744140625,0.4937284588813782,-0.570770263671875,0.6883449554443359,-0.457305908203125,0.6888943314552307,-0.463165283203125,0.3668324947357178,-0.557464599609375,0.504593014717102,-0.757354736328125,0.7612537145614624,-0.9835205078125,0.7646412253379822,-0.816192626953125,0.3722037374973297,-0.7119140625,0.3036896884441376,-0.384613037109375,0.538651704788208,-0.674407958984375,0.8586382865905762,-0.87530517578125,0.4837794005870819,-0.748321533203125,0.39219337701797485,-0.89385986328125,0.8168889284133911,-0.383087158203125,0.41496017575263977,-0.57177734375,0.3869746923446655,-0.77130126953125,0.443464457988739,-0.49298095703125,0.826410710811615,-0.50286865234375,0.7165135741233826,-0.877899169921875,0.6801660060882568,-0.453033447265625,0.7834406495094299,-0.641021728515625,0.3904538154602051,-0.759033203125,0.7140110731124878,-0.32427978515625,0.5309305191040039,-0.64324951171875,0.41636401414871216,-0.30572509765625,0.8161565065383911,-0.80023193359375,0.48017823696136475,-0.782623291015625,0.22040466964244843,-0.606231689453125,0.42402416467666626,-0.388580322265625,0.5926083922386169,-0.7998046875,0.3341776728630066,-0.489501953125,0.3228248059749603,-0.131256103515625,0.6221198439598083,-0.41595458984375,0.460890531539917,-0.351043701171875,0.31995606422424316,-0.8848876953125,0.34458449482917786,-0.337249755859375,0.7983947396278381,-0.58074951171875,0.41022980213165283,-0.598785400390625,0.7505111694335938,-0.17919921875,0.3888973593711853,-0.32196044921875,0.5402386784553528,-0.881195068359375,0.6035035252571106,-0.595703125,0.47276222705841064,-0.50213623046875,0.7525864243507385,-0.254302978515625,0.4389171898365021,-0.46484375,0.5334635376930237,-0.476898193359375,0.4210943877696991,-0.310516357421875,0.6028321385383606,-0.7694091796875,0.5495772957801819,-0.801116943359375,0.47901850938796997,-0.542877197265625,0.3263344168663025,-0.4500732421875,0.504531979560852,-0.26849365234375,0.32215338945388794,-0.68084716796875,0.5980101823806763,-0.57745361328125,0.904324471950531,-0.835662841796875,0.4346140921115875,-0.53070068359375,0.37031158804893494,-0.8118896484375,0.6049073934555054,-0.467681884765625,0.43174535036087036,-0.230133056640625,0.3155613839626312,-0.3326416015625,0.5947141647338867,-0.582489013671875,0.28116703033447266,-0.35809326171875,0.5801873803138733,-0.582122802734375,0.65208899974823,-0.5673828125,0.6040528416633606,-0.42047119140625,0.4856410324573517,-0.619476318359375,0.4759056270122528,-0.699371337890625,0.46305733919143677,-0.56689453125,0.4689168930053711,-0.284637451171875,0.287240207195282,-0.79034423828125,0.4189580976963043,-0.654266357421875,0.43931394815444946,-0.4593505859375,0.3267006576061249,-0.546173095703125,0.43241676688194275,-0.836456298828125,0.5488753914833069,-0.79437255859375,0.7584459781646729,-0.29620361328125,0.868617832660675,-0.731170654296875,0.5367595553398132,-0.793121337890625,0.4982451796531677,-0.30767822265625,0.6829127073287964,-0.47735595703125,0.3888973593711853,-0.746978759765625,0.42451247572898865,-0.675872802734375,0.7142552137374878,-0.41326904296875,0.4498428404331207,-0.692779541015625,0.6938077807426453,-0.59185791015625,0.7419354915618896,-0.53253173828125,0.45689260959625244,-0.398101806640625,0.730948805809021,-0.439208984375,0.711966335773468,-0.5159912109375,0.6718649864196777,-0.700592041015625,0.5684682726860046,-0.472808837890625,0.48789942264556885,-0.434906005859375,0.7882320880889893,-0.861846923828125,0.5716117024421692,-0.540496826171875,0.14719076454639435,-0.9000244140625,0.862025797367096,-0.570648193359375,0.48054444789886475,-0.707733154296875,0.7165135741233826,-0.54486083984375,0.3740653693675995,-0.695709228515625,0.7574694156646729,-0.73236083984375,0.28073975443840027,-0.86669921875,0.35212257504463196,-0.922271728515625,0.7547532320022583,-0.65460205078125,0.5341044068336487,-0.870025634765625,0.4192022383213043,-0.320343017578125,0.7058625817298889,-0.68133544921875,0.2844935357570648,-0.932891845703125,0.9508652091026306,-0.666717529296875,0.8205816745758057,-0.63568115234375,0.5356608629226685,-0.867706298828125,0.7680593132972717,-0.7318115234375,0.4901272654533386,-0.604339599609375,0.5907162427902222,-0.619659423828125,0.8187810778617859,-0.781646728515625,0.540055513381958,-0.7467041015625,0.7103793621063232,-0.6109619140625,0.42283394932746887,-0.512725830078125,0.5272072553634644,-0.68756103515625,0.5238196849822998,-0.7642822265625,0.8828089237213135,-0.633331298828125,0.41340371966362,-0.462860107421875,0.5106051564216614,-0.5791015625,0.6089358329772949,-0.353118896484375,0.6794641017913818,-0.79986572265625,0.5674611926078796,-0.70123291015625,0.9305703639984131,-0.847442626953125,0.2220526784658432,-0.4776611328125,0.7975707054138184,-0.493927001953125,0.3093966543674469,-0.86688232421875,0.5025482773780823,-0.949188232421875,0.5077974796295166,-0.856597900390625,0.4295480251312256,-0.6455078125,0.5377666354179382,-0.971832275390625,0.4663838744163513,-0.397186279296875,0.5824762582778931,-0.6654052734375,0.347209095954895,-0.433319091796875,0.7575914859771729,-0.6605224609375,0.5636463761329651,-0.620025634765625,0.4850001633167267,-0.525787353515625,0.3430890738964081,-0.59326171875,0.6587725281715393,-0.3211669921875,0.9106417894363403,-0.364532470703125,0.5103915333747864,-0.6702880859375,0.7090670466423035,-0.46832275390625,0.5256202816963196,-0.7149658203125,0.634083092212677,-0.826385498046875,0.6439710855484009,-0.69146728515625,0.49491867423057556,-0.627288818359375,0.38450270891189575,-0.487457275390625,0.48170414566993713,-0.40966796875,0.6772972941398621,-0.369049072265625,0.7211218476295471,-0.4840087890625,0.9333170652389526,-0.885833740234375,0.5659047365188599,-0.60650634765625,0.522110641002655,-0.62109375,0.5157628059387207,-0.07489013671875,0.6865444183349609,-0.341552734375,0.4991912543773651,-0.4908447265625,0.48008668422698975,-0.6953125,0.7023530006408691,-0.4886474609375,0.5467391014099121,-0.532501220703125,0.5612353682518005,-0.8360595703125,0.47270119190216064,-0.43682861328125,0.9194616675376892,-0.7095947265625,0.6872158050537109,-0.80645751953125,0.33765679597854614,-0.64208984375,0.5144199728965759,-0.67584228515625,0.7887203693389893,-0.66876220703125,0.5529038310050964,-0.65185546875,0.7249366641044617,-0.811920166015625,0.6535844206809998,-0.728240966796875,0.6534928679466248,-0.99456787109375,0.4694662392139435,-0.104278564453125,0.574236273765564,-0.66485595703125,0.557267963886261,-0.46673583984375,0.5421613454818726,-0.173309326171875,0.28110599517822266,-0.587188720703125,0.8556169271469116,-0.618896484375,0.6276131272315979,-0.560089111328125,0.46449172496795654,-0.3564453125,0.772759199142456,-0.224761962890625,0.44804224371910095,-0.36114501953125,0.12283699959516525,-0.8157958984375,0.7810297012329102,-0.342041015625,0.6977141499519348,-0.790924072265625,0.7109286785125732,-0.743438720703125,0.4506057798862457,-0.8433837890625,0.7598803639411926,-0.430633544921875,0.9010894894599915,-0.442535400390625,0.4136784076690674,-0.70458984375,0.4029969274997711,-0.554290771484375,0.8196051120758057,-0.720001220703125,0.7097079157829285,-0.53680419921875,0.3076876103878021,-0.72674560546875,0.36915189027786255,-0.35546875,0.6659138798713684,-0.373626708984375,0.6728720664978027,-0.6116943359375,0.6397595405578613,-0.71539306640625,0.52183598279953,-0.484344482421875,0.5287026762962341,-0.56622314453125,0.5183873772621155,-0.814239501953125,0.491439551115036,-0.59906005859375,0.6421094536781311,-0.447540283203125,0.5007781982421875,-0.393798828125,0.4354380965232849,-0.44500732421875,0.39268165826797485,-0.44342041015625,0.2848597764968872,-0.694915771484375,0.4997711181640625,-0.88836669921875,0.3673512935638428,-0.739227294921875,0.695730447769165,-0.565521240234375,0.42628252506256104,-0.69537353515625,0.6070742011070251,-0.595306396484375,0.7236548662185669,-0.60748291015625,0.6874599456787109,-0.70819091796875,0.6333811283111572,-0.67071533203125,0.3496810793876648,-0.717132568359375,0.3429059684276581,-0.51617431640625,0.4066286087036133,-0.817596435546875,0.8892788290977478,-0.4766845703125,0.5341960191726685,-0.754150390625,0.40861231088638306,-0.515594482421875,0.6543778777122498,-0.742279052734375,0.8385570645332336,-0.456878662109375,0.6020081043243408,-0.77215576171875,0.3804132342338562,-1,0.4831995666027069,-0.32318115234375,0.9535508155822754,-0.71246337890625,0.7538377046585083,-0.50732421875,0.5658131837844849,-0.52044677734375,0.33005768060684204,-0.62518310546875,0.652668833732605,-0.537841796875,0.3187047839164734,-0.21234130859375,0.24405652284622192,-0.32000732421875,0.5288857817649841,-0.178802490234375,0.4692525863647461,-0.378143310546875,0.33143100142478943,-0.317840576171875,0.32914212346076965,-0.35443115234375,0.2630085051059723,-0.35943603515625,0.2711264491081238,-0.26025390625,0.39170506596565247,-0.184173583984375,0.460829496383667,-0.31329345703125,0.1825922429561615,-0.324371337890625,0.2559282183647156,-0.371856689453125,0.32242804765701294,-0.384674072265625,0.4945219159126282,-0.308258056640625,0.23261207342147827,-0.41156005859375,0.18094424903392792,-0.31976318359375,0.21790215373039246,-0.33807373046875,0.5282753705978394,-0.348480224609375,0.3645741045475006,-0.318145751953125,0.27332377433776855,-0.21905517578125,0.382641077041626,-0.266937255859375,0.35117650032043457,-0.171234130859375,0.15701773762702942,-0.351318359375,0.2605975568294525,-0.24310302734375,0.14615313708782196,-0.31005859375,0.35316020250320435,-0.262939453125,0.3901180922985077,-0.3115234375,0.44727927446365356,-0.257598876953125,0.395336776971817,-0.274932861328125,0.3751335144042969,-0.500823974609375,0.3495284914970398,-0.465728759765625,0.16125980019569397,-0.37994384765625,0.35273292660713196,-0.418121337890625,0.4778588116168976,-0.38397216796875,0.347636342048645,-0.313201904296875,0.22537919878959656,-0.32659912109375,0.3893551528453827,-0.34161376953125,0.16278572380542755,-0.44219970703125,0.3419293761253357,-0.33843994140625,0.5838801264762878,-0.474700927734375,0.27246925234794617,-0.514556884765625,0.5138401389122009,-0.409820556640625,0.158787801861763,-0.51220703125,0.2774132490158081,-0.176177978515625,0.317178875207901,-0.41241455078125,0.2054506093263626,-0.421630859375,0.443617045879364,-0.253265380859375,0.22013001143932343,-0.30047607421875,0.3500778079032898,-0.363525390625,0.43165379762649536,-0.31988525390625,0.20065920054912567,-0.295989990234375,0.33075961470603943,-0.28167724609375,0.3662221133708954,-0.142059326171875,0.40861231088638306,-0.27459716796875,0.40018922090530396,-0.107452392578125,0.2297128140926361,-0.30657958984375,0.4600054919719696,-0.332275390625,0.7442854046821594,-0.404754638671875,0.2916043698787689,-0.0811767578125,0.252449095249176,-0.51123046875,0.5859248638153076,-0.741546630859375,0.40043336153030396,-0.845611572265625,0.6563310623168945,-0.4796142578125,0.6930448412895203,-0.55328369140625,0.3337809443473816,-0.674530029296875,0.8405102491378784,-0.435699462890625,0.5442976355552673,-0.309417724609375,0.539994478225708,-0.7259521484375,0.5903805494308472,-0.387603759765625,0.7806940078735352,-0.63287353515625,0.634327232837677,-0.605804443359375,0.6723532676696777,-0.557159423828125,0.5703299045562744,-0.583465576171875,0.3332315981388092,-0.810455322265625,0.6619159579277039,-0.702667236328125,0.6768395304679871,-0.184844970703125,0.49516281485557556,-0.820281982421875,0.7111423015594482,-0.769775390625,0.6118960976600647,-0.397125244140625,0.5980712175369263,-0.948028564453125,0.7581102848052979,-0.61846923828125,0.4945524334907532,-0.516845703125,0.8538773655891418,-0.644195556640625,0.6989654302597046,-0.923004150390625,0.4268013536930084,-0.752227783203125,0.652974009513855,-0.682373046875,0.8045594692230225,-0.374481201171875,0.6049379110336304,-0.60784912109375,0.7954344153404236,-0.39251708984375,0.6123234033584595,-0.712799072265625,0.7634510397911072,-0.435028076171875,0.7339396476745605,-0.40716552734375,0.6538590788841248,-0.3765869140625,0.5313272476196289,-0.461456298828125,0.6198614239692688,-0.542724609375,0.574327826499939,-0.467529296875,0.49717703461647034,-0.635162353515625,0.44813379645347595,-0.454986572265625,0.7447736859321594,-0.420654296875,0.6421704888343811,-0.2442626953125,0.7218238115310669,-0.652374267578125,0.43256935477256775,-0.798004150390625,0.8427075743675232,-0.75994873046875,0.7804498672485352,-0.49908447265625,0.8633381128311157,-0.545623779296875,0.5365764498710632,-0.726837158203125,0.6359447240829468,-0.627655029296875,0.15347757935523987,-0.31951904296875,0.3481856882572174,-0.505157470703125,0.38511306047439575,-0.20709228515625,0.29920345544815063,-0.34942626953125,0.7604601979255676,-0.46575927734375,0.25659963488578796,-0.644622802734375,0.47929319739341736,-0.275909423828125,0.4942472577095032,-0.25634765625,0.7843867540359497,-0.657135009765625,0.6386303305625916,-0.64849853515625,0.43165379762649536,-0.54473876953125,0.3005768060684204,-0.332855224609375,0.24237799644470215,-0.450927734375,0.36011841893196106,-0.489654541015625,0.34336376190185547,-0.47906494140625,0.8368480205535889,-0.36712646484375,0.6971648335456848,-0.57940673828125,0.45738089084625244,-0.81976318359375,0.5613269209861755,-0.50830078125,0.634113609790802,-0.568756103515625,0.6685079336166382,-0.475494384765625,0.3234046399593353,-0.519287109375,0.8790551424026489,-0.423675537109375,0.6096377372741699,-0.514007568359375,0.5184789299964905,-0.525543212890625,0.8937956094741821,-0.47540283203125,0.7627491354942322,-0.8101806640625,0.8921476006507874,-0.680389404296875,0.616504430770874,-0.47271728515625,0.5672475099563599,-0.579498291015625,0.69478440284729,-0.4718017578125,0.7754753232002258,-0.69390869140625,0.7437666058540344,-0.65692138671875,0.634723961353302,-0.534393310546875,0.45521408319473267,-0.684417724609375,0.6626788973808289,-0.51171875,0.4936674237251282,-0.640655517578125,0.4456923007965088,-0.68353271484375,0.32865384221076965,-0.82696533203125,0.6498916745185852,-0.7030029296875,0.45823541283607483,-0.65716552734375,0.25138095021247864,-0.495391845703125,0.5810724496841431,-0.872650146484375,0.8479567766189575,-0.436920166015625,0.6911221742630005,-0.556365966796875,0.6065554022789001,-0.83935546875,0.5532090067863464,-0.553619384765625,0.8528092503547668,-0.5721435546875,0.8135319352149963,-0.567779541015625,0.6445509195327759,-0.571319580078125,0.365703284740448,-0.6890869140625,0.8778038620948792,-0.526519775390625,0.4664754271507263,-0.580322265625,0.7157505750656128,-0.6663818359375,0.6598101854324341,-0.816925048828125,0.6115909218788147,-0.3250732421875,0.7859736680984497,-0.704345703125,0.8062685132026672,-0.548065185546875,0.7069612741470337,-0.73468017578125,0.4208502471446991,-0.668792724609375,0.6774498820304871,-0.895111083984375,0.5534226298332214,-0.17547607421875,0.7332987189292908,-0.6749267578125,0.3097018301486969,-0.741363525390625,0.6634113788604736,-0.56494140625,0.33622241020202637,-0.536651611328125,0.6031067967414856,-0.357452392578125,0.886837363243103,-0.719879150390625,0.6129032373428345,-0.67694091796875,0.8066652417182922,-0.680328369140625,0.8427686095237732,-0.53765869140625,0.8011108636856079,-0.73004150390625,0.6051820516586304,-0.36572265625,0.8243659734725952,-0.715484619140625,0.765434741973877,-0.9256591796875,0.8306832909584045,-0.450164794921875,0.4203619360923767,-0.56451416015625,0.5145725607872009,-0.444854736328125,0.5634326934814453,-0.81939697265625,0.7558824419975281,-0.8375244140625,0.7058320641517639,-0.54425048828125,0.7850276231765747,-0.347412109375,0.5091097950935364,-0.8575439453125,0.9356364607810974,-0.42059326171875,0.32193976640701294,-0.770721435546875,0.7412030100822449,-0.621307373046875,0.5970031023025513,-0.857635498046875,0.8806726336479187,-0.62591552734375,0.8063600659370422,-0.276214599609375,0.8343760371208191,-0.656494140625,0.7893307209014893,-0.7802734375,0.5595263242721558,-0.605438232421875,0.6335031986236572,-0.423583984375,0.4427625238895416,-0.430755615234375,0.5143284201622009,-0.608367919921875,0.5273598432540894,-0.6390380859375,0.5682851672172546,-0.65924072265625,0.5487838387489319,-0.642120361328125,0.617450475692749,-0.551605224609375,0.47190770506858826,-0.81658935546875,1,-0.883331298828125,0.6312448382377625,-0.64459228515625,0.4763634204864502,-0.470489501953125,0.4867091774940491,-0.770111083984375,0.3192236125469208,-0.619354248046875,0.23932614922523499,-0.523223876953125,0.6133610010147095,-0.618255615234375,0.6660969853401184,-0.90655517578125,0.5285500884056091,-0.84686279296875,0.6397900581359863,-0.58984375,0.25888851284980774,-0.461944580078125,0.6317026019096375,-0.78753662109375,0.6461989283561707,-0.603424072265625,0.5629138946533203,-0.4964599609375,0.6493728160858154,-0.66107177734375,0.6725668907165527,-0.808319091796875,0.43406474590301514,-0.389617919921875,0.7442854046821594,-0.85345458984375,0.9022492170333862,-0.573638916015625,0.8505813479423523,-0.333953857421875,0.2865382730960846,-0.639678955078125,0.7107150554656982,-0.535736083984375,0.5855891704559326,-0.850433349609375,0.7551500201225281,-0.567718505859375,0.42332223057746887,-0.514923095703125,0.6151615977287292,-0.82049560546875,0.5093539357185364,-0.73443603515625,0.8662984371185303,-0.320037841796875,0.5767998099327087,-0.734100341796875,0.7500534057617188,-0.830535888671875,0.8970305323600769,-0.693572998046875,0.41459396481513977,-0.701385498046875,0.5838496088981628,-0.4654541015625,0.287606418132782,-0.55267333984375,0.5080721378326416,-0.257049560546875,0.8063295483589172,-0.607757568359375,0.5460371971130371,-0.549896240234375,0.8583330512046814,-0.492767333984375,0.4382458031177521,-0.570526123046875,0.7231360673904419,-0.66326904296875,0.5781731605529785,-0.614227294921875,0.6358836889266968,-0.384490966796875,0.5153965950012207,-0.653472900390625,0.3799859583377838,-0.538909912109375,0.8118534088134766,-0.560455322265625,0.7022614479064941,-0.423309326171875,0.6854762434959412,-0.49273681640625,0.3017365038394928,-0.65838623046875,0.29718923568725586,-0.707977294921875,0.6772057414054871,-0.684967041015625,0.8171330690383911,-0.6383056640625,0.4371471405029297,-0.751922607421875,0.3486434519290924,-0.401885986328125,0.8026978373527527,-0.60797119140625,0.4294869899749756,-0.667572021484375,0.44773703813552856,-0.84765625,0.8060548901557922,-0.558013916015625,0.7826471924781799,-0.708221435546875,0.5118259191513062,-0.394744873046875,0.35209205746650696,-0.8370361328125,0.8286690711975098,-0.27423095703125,0.6054261922836304,-0.607879638671875,0.7677541375160217,-0.4366455078125,0.6416516900062561,-0.364410400390625,0.6627094149589539,-0.65716552734375,0.808465838432312,-0.842681884765625,0.599291980266571,-0.44287109375,0.5841242671012878,-0.59051513671875,0.3870357275009155,-0.66436767578125,0.6305734515190125,-0.78277587890625,0.7693105936050415,-0.761566162109375,0.7080599665641785,-0.90826416015625,0.6899014115333557,-0.69647216796875,0.6337168216705322,-0.6636962890625,0.7508773803710938,-0.5728759765625,0.7011322379112244,-0.67388916015625,0.9309061169624329,-0.56732177734375,0.35306864976882935,-0.600433349609375,0.8018738627433777,-0.669158935546875,0.7412641048431396,-0.704803466796875,0.6777855753898621,-0.663787841796875,0.383068323135376,-0.86859130859375,0.9090242981910706,-0.75384521484375,0.3245338201522827,-0.821319580078125,0.4754478633403778,-0.767822265625,0.5885494351387024,-0.47369384765625,0.6070436835289001,-0.554107666015625,0.5538194179534912,-0.625396728515625,0.670522153377533,-0.591278076171875,0.26450392603874207,-0.68621826171875,0.3897823989391327,-0.3385009765625,0.4753257930278778,-0.6427001953125,0.45814386010169983,-0.48455810546875,0.8963286280632019,-0.606475830078125,0.6081117987632751,-0.474700927734375,0.3481856882572174,-0.361236572265625,0.4676961600780487,-0.443878173828125,0.5077669620513916,-0.501861572265625,0.43168431520462036,-0.33782958984375,0.3784905672073364,-0.54925537109375,0.4990997016429901,-0.455474853515625,0.24417859315872192,-0.5546875,0.4284493625164032,-0.402008056640625,0.4987640082836151,-0.503021240234375,0.6038087010383606,-0.65924072265625,0.7140415906906128,-0.670379638671875,0.14081239700317383,-0.537994384765625,0.8488723635673523,-0.606048583984375,0.5653858780860901,-0.5224609375,0.7037873268127441,-0.40631103515625,0.7380901575088501,-0.388885498046875,0.7004913687705994,-0.443939208984375,0.4197515845298767,-0.37152099609375,0.8158513307571411,-0.573974609375,0.5690481066703796,-0.797637939453125,0.5170446038246155,-0.4759521484375,0.5832392573356628,-0.872711181640625,0.3986937999725342,-0.616455078125,0.2631610929965973,-0.6873779296875,0.8595843315124512,-0.6563720703125,0.8891567587852478,-0.242462158203125,0.617847204208374,-0.537750244140625,0.6933805346488953,-0.361114501953125,0.6831568479537964,-0.713531494140625,0.4741660952568054,-0.49005126953125,0.44047364592552185,-0.591827392578125,0.22367015480995178,-0.5997314453125,0.7806940078735352,-0.674560546875,0.9338968992233276,-0.4730224609375,0.9265419244766235,-0.522552490234375,0.43375957012176514,-0.49395751953125,0.37836846709251404,-0.50787353515625,0.5274208784103394,-0.49334716796875,0.4193853437900543,-0.714508056640625,0.4537492096424103,-0.792755126953125,0.4681234061717987,-0.531341552734375,0.6259040832519531,-0.431884765625,0.574938178062439,-0.308929443359375,0.6259346008300781,-0.551544189453125,0.730979323387146,-0.4005126953125,0.7752922177314758,-0.64215087890625,0.5757927298545837,-0.817901611328125,0.7414472103118896,-0.4979248046875,0.8305917382240295,-0.374725341796875,0.5789361000061035,-0.757843017578125,0.6382946372032166,-0.540679931640625,0.5446028113365173,-0.484039306640625,0.5245521664619446,-0.505767822265625,0.7228308916091919,-0.602325439453125,0.713248074054718,-0.327880859375,0.652302622795105,-0.658447265625,0.7508468627929688,-0.5191650390625,0.5894345045089722,-0.7833251953125,0.5956602692604065,-0.668609619140625,0.6423535943031311,-0.582550048828125,0.7093417048454285,-0.665435791015625,0.65147864818573,-0.68804931640625,0.3269142806529999,-0.857574462890625,0.651692271232605,-0.35052490234375,0.7078462839126587,-0.80743408203125,0.7560960650444031,-0.478973388671875,0.6552934646606445,-0.61328125,0.5366374850273132,-0.59918212890625,0.4206976592540741,-0.725921630859375,0.44917142391204834,-0.60552978515625,0.8275094032287598,-0.371490478515625,0.6684468984603882,-0.73052978515625,0.9470198750495911,-0.68719482421875,0.7834101319313049,-0.614593505859375,0.8930631279945374,-0.369873046875,0.9383525848388672,-0.955474853515625,0.48033082485198975,-0.552520751953125,0.9930417537689209,-0.6328125,0.9055757522583008,-0.67010498046875,0.6542558073997498,-0.430419921875,0.7267067432403564,-0.784820556640625,0.3811761736869812,-0.67279052734375,0.8199713230133057,-0.340911865234375,0.44755393266677856,-0.6302490234375,0.9287087321281433,-0.685302734375,0.8890346884727478,-0.536407470703125,0.6074709296226501,-0.36541748046875,0.6643269062042236,-0.556182861328125,0.5711538791656494,-0.57952880859375,0.7430341243743896,-0.415191650390625,0.730399489402771,-0.464813232421875,0.8273262977600098,-0.778472900390625,0.3983275890350342,-0.464874267578125,0.5631275177001953,-0.43023681640625,0.6059449911117554,-0.549163818359375,0.8510696887969971,-0.56390380859375,0.52214115858078,-0.61578369140625,0.460737943649292,-0.667022705078125,0.4520096480846405,-0.5921630859375,0.5277870893478394,-0.36407470703125,0.52189701795578,-0.221405029296875,0.5805230736732483,-0.210418701171875,0.7274696826934814,-0.566802978515625,0.39204075932502747,-0.590240478515625,0.4837794005870819,-0.530517578125,0.599871814250946,-0.45654296875,0.21677297353744507,-0.83172607421875,0.5890377759933472,-0.746856689453125,0.7365031838417053,-0.510223388671875,0.730521559715271,-0.321929931640625,0.4663838744163513,-0.6656494140625,0.5064851641654968,-0.47161865234375,0.4941557049751282,-0.632080078125,0.6158329844474792,-0.44244384765625,0.20926542580127716,-0.457305908203125,0.6660969853401184,-0.587799072265625,0.5234839916229248,-0.420257568359375,0.6444288492202759,-0.5338134765625,0.4348277151584625,-0.53692626953125,0.5593127012252808,-0.39862060546875,0.5095980763435364,-0.625518798828125,0.3659169375896454,-0.41705322265625,0.29813531041145325,-0.343841552734375,0.3814203441143036,-0.327972412109375,0.3091219961643219,-0.291259765625,0.3257851004600525,-0.4383544921875,0.6105533242225647,-0.17498779296875,0.3021332323551178,-0.444854736328125,0.5689565539360046,-0.2440185546875,0.3983581066131592,-0.305511474609375,0.5339518189430237,-0.547210693359375,0.29081088304519653,-0.3758544921875,0.21011993288993835,-0.35382080078125,0.2925504446029663,-0.4281005859375,0.503372311592102,-0.55450439453125,0.5532395243644714,-0.6083984375,0.32172611355781555,-0.368011474609375,0.5919675230979919,-0.319793701171875,0.4185308516025543,-0.402191162109375,0.4543290436267853,-0.364532470703125,0.39326152205467224,-0.3194580078125,0.3876766264438629,-0.280059814453125,0.26783043146133423,-0.4517822265625,0.3033539950847626,-0.510650634765625,0.5266579389572144,-0.184661865234375,0.2777489423751831,-0.403717041015625,0.47825556993484497,-0.339111328125,0.34629353880882263,-0.3216552734375,0.5486922860145569,-0.27099609375,0.49574267864227295,-0.3414306640625,0.18451490998268127,-0.230438232421875,0.5305337905883789,-0.413177490234375,0.20944853127002716,-0.25897216796875,0.4831385314464569,-0.248565673828125,0.39161351323127747,-0.384368896484375,0.36051514744758606,-0.345123291015625,0.2531815469264984,-0.388916015625,0.251838743686676,-0.339111328125,0.2617572546005249,-0.250457763671875,0.20343638956546783,-0.329620361328125,0.17069002985954285,-0.281402587890625,0.2070375680923462,-0.152130126953125,0.2455519288778305,-0.249176025390625,0.19522690773010254,-0.224395751953125,0.20413830876350403,-0.185577392578125,0.1984618604183197,-0.180938720703125,0.13992737233638763,-0.15814208984375,0.1422772854566574,-0.181640625,0.08310189843177795,-0.18017578125,0.08020263910293579,-0.115264892578125,0.14676351845264435,-0.075164794921875,0.10388500988483429,-0.049713134765625,0.04602191224694252,-0.05517578125,0,0];

@Component({
  selector: 'app-audio-player',
  templateUrl: './audio-player.component.html',
  styleUrls: ['./audio-player.component.scss']
})


export class AudioPlayerComponent implements OnInit {
  public isShuffleOn: boolean;
  public isTrackRepeated: boolean;
  public isReady: boolean = false;
  public isRecordPlayed: boolean;
  public records: Array<AudioRecord>;
  public currentPlayedTrack: any = null;
  public wavesurfer: any = null;
  public streamTrack: string = null;
  public currentTime: number;
  public durationTime: number;
  private initialize: any;

  constructor(
    private _sharedService: SharedService,
    public zone: NgZone
  ) {}

  initWavesurfer() {
    if (!this.wavesurfer) {
      this.wavesurfer = WaveSurfer.create({
        container: '#waveform',
        backend: 'MediaElement',
        height: 70,
        progressColor: '#c23a48',
        cursorColor: '#fff',
        barWidth: 1.5
      });

      this.wavesurfer.load(this.streamTrack, peaks);

      this.wavesurfer.on('audioprocess', () => {
        this.zone.run(() => {
          this.currentTime = this.isReady ? this.wavesurfer.getCurrentTime() : 0;
        });
      });

      this.wavesurfer.on('finish', () => {
        if(this.isTrackRepeated) {
            this.repeatTrack();
        } else if(this.isShuffleOn) {
            this.shuffleTracks();
        } else {
          this.playNextTrack();
        }
      });

    } else {
      this.initialize();
      this.wavesurfer.load(this.streamTrack, peaks);
    }
  }

  ngOnInit() {
    this.initialize = _.once(() => {
      this.wavesurfer.on('ready', () => {
        this.playTrack();
      });
    });

    this.getCurrentPlayList();
    this._sharedService.playTrackSubject.subscribe((track: any) => {
      this.setCurrentPlayedTrack(track);
      this.initialize();
    });
  }

  getCurrentPlayList(): void {
    this._sharedService.getMusic().subscribe(data => {
      this.records = data.records;
      this.setCurrentPlayedTrack(this.records[0]);
    }, err => console.error(err));
  }

  setCurrentPlayedTrack(track) {
    this.currentPlayedTrack = track;
    this._sharedService.getTrackLink(track.trackLink).subscribe(record => {
      this.streamTrack = record.stream_url + '?nor=1';
      this.durationTime = record.duration;
      this.initWavesurfer();
    });
  }

  playTrack() {
    this.isReady = true;
    this.isRecordPlayed = true;
    this.wavesurfer.play();
  }

  pauseTrack() {
    this.isRecordPlayed = false;
    this.wavesurfer.pause();
  }

  stopTrack() {
    this.isRecordPlayed = false;
    this.wavesurfer.stop();
  }

  toggleRepeat() {
    this.isTrackRepeated = !this.isTrackRepeated
  }

  repeatTrack() {
    let index = this.getCurrentAudioTrackIndex();
    this.setCurrentPlayedTrack(this.records[index]);
  }

  shuffleTracks() {
    let randomTrack = this.records[Math.floor(Math.random()*this.records.length)];
    this.setCurrentPlayedTrack(randomTrack);
  }

  toggleShuffle() {
    this.isShuffleOn = !this.isShuffleOn;
  }

  getAudioTrackById(id: number) {
    return this.records.find((elem) => <number>elem.id === id);
  }

  playNextTrack() {
    if(this.isShuffleOn) {
      this.shuffleTracks();
    } else {
      this.setCurrentPlayedTrack(this.getNextTrack());
    }
  }

  playPreviousTrack() {
    this.setCurrentPlayedTrack(this.getPreviousTrack());
  }

  getNextTrack(): AudioRecord {
    const currentTrackIndex = this.getCurrentAudioTrackIndex();
    if (currentTrackIndex == this.records.length - 1) {
      return this.records[0];
    }
    return this.records[currentTrackIndex + 1];
  }

  getPreviousTrack(): AudioRecord {
    const currentTrackIndex = this.getCurrentAudioTrackIndex();
    if (+currentTrackIndex === 0) {
      return this.records[this.records.length - 1];
    }
    return this.records[currentTrackIndex - 1];
  }

  getCurrentAudioTrackIndex() {
    return this.records.indexOf(this.getAudioTrackById(this.currentPlayedTrack.id));
  }

}
