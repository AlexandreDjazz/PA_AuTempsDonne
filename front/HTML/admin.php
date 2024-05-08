<?php include("../includes/loadLang.php");?>

<?php include("../includes/checkRights.php");?>

<!DOCTYPE html>
<html>
	<head>

		<?php include("../includes/head.php"); ?>

		<title><?php echo($data["admin"]["title"]) ?></title>
	</head>
	<body>

		<?php include("../includes/header.php");?>

		<main>

			<section class="flex flexCenter wrap">
				<h1 class="width100 textCenter noMarginBottom"><?php echo($data["admin"]["title"]) ?></h1>
			</section>

            <section class="flex flexAround alignCenter height90">
                <div id="ticket" class="box width20">
                    <h1 class="textCenter bold underline"><?php echo($data["ticket"]["title"]) ?></h1>
                    <ul class="ul">
                        <li><a style="color: #0c2124" href="<?php echo($address["ticket"]) ?>/list?me=true" target="_blank"><?php echo($data["ticket"]["me"]) ?></a></li>
                        <li><a style="color: #0c2124" href="<?php echo($address["ticket"]) ?>/list?assign=false" target="_blank"><?php echo($data["ticket"]["assignFalse"]) ?></a></li>
                        <li><a style="color: #0c2124" href="<?php echo($address["ticket"]) ?>/list" target="_blank"><?php echo($data["ticket"]["all"]) ?></a></li>
                    </ul>
                </div>

                <div id="users" class="box width20">
                    <h1 class="textCenter bold underline"><?php echo($data["user"]["title"]) ?></h1>
                    <ul class="ul">
                        <li><a style="color: #0c2124" href="" target="_blank"><?php echo($data["user"]["title"]) ?></a></li>
                        <li><a style="color: #0c2124" href="" target="_blank"><?php echo($data["user"]["title"]) ?></a></li>
                        <li><a style="color: #0c2124" href="" target="_blank"><?php echo($data["user"]["title"]) ?></a></li>
                    </ul>
                </div>

                <div id="storehouse" class="box width20">
                    <h1 class="textCenter bold underline"><?php echo($data["storehouse"]["title"]) ?></h1>
                    <ul class="ul">
                        <li><a style="color: #0c2124" href="" target="_blank"><?php echo($data["storehouse"]["title"]) ?></a></li>
                        <li><a style="color: #0c2124" href="" target="_blank"><?php echo($data["storehouse"]["title"]) ?></a></li>
                        <li><a style="color: #0c2124" href="" target="_blank"><?php echo($data["storehouse"]["title"]) ?></a></li>
                    </ul>
                </div>
            </section>

		</main>

		<?php include("../includes/footer.php");?>

	</body>
</html>


