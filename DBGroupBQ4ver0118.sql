CREATE DATABASE IF NOT EXISTS `GroupBQ4` DEFAULT CHARACTER SET utf8 ;
USE `GroupBQ4` ;

-- -----------------------------------------------------
-- Table `GroupBQ4`.`member`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `GroupBQ4`.`member` (
  `username` VARCHAR(9) NOT NULL,
  `password` VARCHAR(128) NOT NULL,
  `stuname` VARCHAR(50) NOT NULL,
  `faculty` VARCHAR(15) NOT NULL,
  `department` VARCHAR(30) NOT NULL,
  `level` INT UNSIGNED NOT NULL,
  `phone` VARCHAR(11) NULL DEFAULT '未入力',
  PRIMARY KEY (`username`),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC))
ENGINE = InnoDB;



-- -----------------------------------------------------
-- Table `GroupBQ4`.`appoint`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `GroupBQ4`.`appoint` (
 `id` MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL COMMENT '一人の学生が一つの予約しか取れない',
  `date` VARCHAR(20) NOT NULL,
  `time` INT(11) UNSIGNED NOT NULL COMMENT '時間順は１から８まで八つに分ける、詳しい時間が時間表に参照',
  `satus` TINYINT(4) NOT NULL COMMENT '1有効予約/0無効予約',
  `comment` VARCHAR(200) NULL DEFAULT '特になし' COMMENT '200文字まで',
  INDEX `fk_appoint_member_idx` (`username` ASC),
  PRIMARY KEY (`id`, `username`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  CONSTRAINT `fk_appoint_member`
    FOREIGN KEY (`username`)
    REFERENCES `GroupBQ4`.`member` (`username`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- update 2023/01/06 
-- 教員情報消す、関連キー消す
-- 日付の月日を一つの列にまとめる（文字列型１０文字まで、ｈｔｍｌ文のinput type ="day"よりYYYY-MM-DDフォームの日付情報を格納する）
-- 予約情報テーブルの名前を若干修正
	-- コメント入力されてない時、デフォルト「特になし」
	-- 予約情報.状態 ルール：ウェブアプリで予約を取た時点で、１になる -→ 複数予約不可；キャンセルまたは出席を取た（予想：管理者操作）場合に0になる -→ 予約可能になる

-- update 2023/01/12
-- 時間表落とす、カラム名を英語に変更（バク避け）

