<?php
class PerlinNoise
{
    private $p = [];

    public function __construct()
    {
        $p = range(0, 255);
        shuffle($p);
        $this->p = array_merge($p, $p);
    }

    private function fade($t)
    {
        return $t * $t * $t * ($t * ($t * 6 - 15) + 10);
    }

    private function lerp($t, $a, $b)
    {
        return $a + $t * ($b - $a);
    }

    private function grad($hash, $x, $y, $z)
    {
        $h = $hash & 15;
        $u = $h < 8 ? $x : $y;
        $v = $h < 4 ? $y : (($h === 12 || $h === 14) ? $x : $z);
        return (($h & 1) === 0 ? $u : -$u) + (($h & 2) === 0 ? $v : -$v);
    }

    public function noise($x, $y, $z)
    {
        $X = (int) floor($x) & 255;
        $Y = (int) floor($y) & 255;
        $Z = (int) floor($z) & 255;
        $x -= floor($x);
        $y -= floor($y);
        $z -= floor($z);
        $u = $this->fade($x);
        $v = $this->fade($y);
        $w = $this->fade($z);
        $A = $this->p[$X] + $Y;
        $AA = $this->p[$A] + $Z;
        $AB = $this->p[$A + 1] + $Z;
        $B = $this->p[$X + 1] + $Y;
        $BA = $this->p[$B] + $Z;
        $BB = $this->p[$B + 1] + $Z;

        return $this->lerp(
            $w,
            $this->lerp(
                $v,
                $this->lerp(
                    $u,
                    $this->grad($this->p[$AA], $x, $y, $z),
                    $this->grad($this->p[$BA], $x - 1, $y, $z)
                ),
                $this->lerp(
                    $u,
                    $this->grad($this->p[$AB], $x, $y - 1, $z),
                    $this->grad($this->p[$BB], $x - 1, $y - 1, $z)
                )
            ),
            $this->lerp(
                $v,
                $this->lerp(
                    $u,
                    $this->grad($this->p[$AA + 1], $x, $y, $z - 1),
                    $this->grad($this->p[$BA + 1], $x - 1, $y, $z - 1)
                ),
                $this->lerp(
                    $u,
                    $this->grad($this->p[$AB + 1], $x, $y - 1, $z - 1),
                    $this->grad($this->p[$BB + 1], $x - 1, $y - 1, $z - 1)
                )
            )
        );
    }
}

function generate_terrain($size_x, $size_y, $sea_border)
{
    $terrain = [];
    $perlin = new PerlinNoise();

    for ($x = -$size_x; $x <= $size_x; $x++) {
        for ($y = -$size_y; $y <= $size_y; $y++) {
            $noise1 = $perlin->noise($x / 2, $y / 2, 0);
            $noise2 = $perlin->noise($x / 10, $y / 10, 0) * 0.5;
            $noise3 = $perlin->noise($x / 20, $y / 20, 0) * 0.25;
            $noise = $noise1 + $noise2 + $noise3;
            $height = intval(($noise + 1) * 2) + 1;

            // Set height to 0 for sea cells on the side of the map with a random border
            $random_sea_border_x = $sea_border + rand(-2, 2);
            $random_sea_border_y = $sea_border + rand(-2, 2);

            if ($x <= -$size_x + $random_sea_border_x || $x >= $size_x - $random_sea_border_x || $y <= -$size_y + $random_sea_border_y || $y >= $size_y - $random_sea_border_y) {
                $height = 0;
            }

            $terrain[] = ['x' => $x, 'y' => $y, 'height' => $height];
        }
    }

    return $terrain;
}
function update_terrain_in_db($terrain)
{
    include_once('../../../config.php');
    $con = mysqli_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
    if (mysqli_connect_errno()) {
        exit('Failed to connect to MySQL: ' . mysqli_connect_error());
    }

    $stmt = $con->prepare("UPDATE map SET hauteur = ? WHERE position_x = ? AND position_y = ?");
    if (!$stmt) {
        echo "Prepare failed: (" . $con->errno . ") " . $con->error;
        exit;
    }
    foreach ($terrain as $cell) {
        $stmt->bind_param('iii', $cell['height'], $cell['x'], $cell['y']);
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }
    }

    $stmt->close();
    $con->close();
}

$size_x = 50;
$size_y = 50;
$sea_border = 6;

$terrain = generate_terrain($size_x, $size_y, $sea_border);
update_terrain_in_db($terrain);

?>