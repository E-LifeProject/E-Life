<?php


namespace plugin\Config\Data;

use pocketmine\utils\Config;
use pocketmine\Player;

class ChestLookData
{
    private static $instance = null;
    /**
     * @var Config
     */
    private $config;
    public $land;

    public function __construct($plugin)
    {
        $this->config = new Config($plugin->getDataFolder() . "Config.yml", Config::YAML, [
            "CanUseID" => 1
        ]);
        $this->plugin = $plugin;
        self::$instance = $this;
    }

    public static function get(): self
    {
        return self::$instance;
    }

    public function addchestlook(string $name, int $x, int $y, int $z, string $world)
    {
        $id = $this->config->get("CanUseID");
        $this->config->set("CanUseID", $id + 1);
        $this->config->set($id, [
            "所持者" => $name,
            "chestx" => $x,
            "chesty" => $y,
            "chestz" => $z,
            "world" => $world,
            "invites" => [],
        ]);
    }

    public function cheakchestlook(string $formname, int $formx, int $formy, int $formz, string $formworld)
    {
        $end = $this->config->get("CanUseID") - 1;
        for ($i = 0; $i <= $end; $i++) {
            $config = $this->config->get($i);
            $x = $config["chestx"];
            $y = $config["chesty"];
            $z = $config["chestz"];
            $world = $config["world"];
            $name = $config["所持者"];
            if ($x == $formx && $y == $formy && $z == $formz && $world == $formworld && $name == $formname) {
                return true;//見つかったらtrueを返して終了

            }
        }
        return false;//何も見つからなかった場合はfalseを返す(return true;されなかった場合)
    }
    public function removechestlook(string $formname, int $formx, int $formy, int $formz, string $formworld)
    {
        $end = $this->config->get("CanUseID") - 1;
        for ($i = 0; $i <= $end; $i++) {
            $config = $this->config->get($i);
            $x = $config["chestx"];
            $y = $config["chesty"];
            $z = $config["chestz"];
            $world = $config["world"];
            $name = $config["所持者"];
            if ($x == $formx && $y == $formy && $z == $formz && $world == $formworld && $name == $formname) {
                $this->config->remove($i);
            }
        }
    }
        public function getid(string $formname, int $formx, int $formy, int $formz, string $formworld)
    {
        $end = $this->config->get("CanUseID") - 1;
        for ($i = 0; $i <= $end; $i++) {
            $config = $this->config->get($i);
            $x = $config["chestx"];
            $y = $config["chesty"];
            $z = $config["chestz"];
            $world = $config["world"];
            $name = $config["所持者"];
            if ($x == $formx && $y == $formy && $z == $formz && $world == $formworld && $name == $formname) {
                return $i;
            }
        }
    }
    public function Examineplayerland(Player $player)
	{
		$land = [];
		$end = $this->config->get("CanUseID") - 1;
		for ($i = 0; $i <= $end; $i++) {
			$config = $this->config->get($i);
			$formname = $player->getName();
			$name = $config["所持者"];
			if ($formname == $name) {
				$land[] = "$i";
			}
		}
		return $land;
	}
	    public function Examineplayerlandb(Player $player)
		{
			$land = [];
			$end = $this->config->get("CanUseID") - 1;
			for ($i = 0; $i <= $end; $i++) {
				$config = $this->config->get($i);
				$formname = $player->getName();
				$name = $config["所持者"];
				if ($formname == $name) {
					$land[] = ["text" => "ID:$i"];
				}
			}
			return $land;
		}

	public function checkinvite(int $id, string $player){
		$config = $this->config->get($id);
		if($player == $config["所持者"]){
			return 1;
		}elseif (in_array($player,$config["invites"])){
			return 2;
		}else{
			return true;
		}

	}
	public function addinvite(int $id, string $player){
    	$config = $this->config->get($id);
    	$config["invites"][] = $player;
    	$this->config->set($id,$config);

	}
	public function cheackinvites(int $id,string $player){
		$config = $this->config->get($id);
		$invites = $config["invites"];
		if(in_array($player,$invites)){
			return true;
		}else{
			return false;
		}

	}


    public function getconfig(int $id){
        return $this->config->get($id);
    }
        public function setCommandStatus($int, $player){
    		$this->status[($player)] = $int;
    }

    public function getCommandStatus($player){
    		return $this->status[($player)];
    }

    public function save()
    {
        $this->config->save();
    }

}