<?php
	error_reporting(E_ALL);
	set_time_limit(0);

  include "solutions.php";

  CreateDiagram(5,"kickstart5.png", 500, 500, 10, true);
  CreateDiagram(6,"kickstart6.png", 500, 500, 10, true);
  CreateDiagram(7,"kickstart7.png", 500, 500, 9,  true);
  CreateDiagram(8,"kickstart8.png", 500, 500, 7, false);
  CreateDiagram(9,"kickstart9.png", 500, 500, 7, false);

  CreateDiagram(5,"kickstart5_A2.png", 2000, 2000, 30, true);
  CreateDiagram(6,"kickstart6_A2.png", 2000, 2000, 30, true);
  CreateDiagram(7,"kickstart7_A2.png", 2000, 2000, 30, true);
  CreateDiagram(8,"kickstart8_A2.png", 2000, 2000, 21, true);
  CreateDiagram(9,"kickstart9_A2.png", 2000, 2000, 21, true);

function CreateDiagram($nPPNs, $sFilename, $nWidth, $nHeight, $nFontSize, $bSuffix)
{
  $font = 'C:\windows\fonts\arial.ttf';

	$PI = 3.1415927;
	$nBigRadius = $nWidth * 0.4;
  
  if ($nPPNs < 8)
  	$nSmallRadius = $nWidth * 0.07;
  else
  	$nSmallRadius = $nWidth * 0.05;
  
	$xCentre = $nWidth / 2;
	$yCentre = $nHeight / 2;

  if ($nPPNs < 6)
    $nPetalHeight = $nWidth * 0.16;
  else if ($nPPNs < 7)
    $nPetalHeight = $nWidth * 0.18;
  else if ($nPPNs < 9)
    $nPetalHeight = $nWidth * 0.12;
  else
    $nPetalHeight = $nWidth * 0.08;

  if ($nPPNs < 6)
    $nPetalWidth = $nWidth * 0.040;
  else if ($nPPNs < 8)
    $nPetalWidth = $nWidth * 0.035;
  else if ($nPPNs < 9)
    $nPetalWidth = $nWidth * 0.025;
  else
    $nPetalWidth = $nWidth * 0.020;

  $nTextRadius = $nSmallRadius + ($nPetalHeight / 5.0);

  global $solutions;
  $solution  = $solutions[$nPPNs];
      
	$img = imagecreatetruecolor($nWidth,$nHeight);
	$black = imageColorAllocate($img, 0, 0, 0);
	$white = imageColorAllocate($img, 255, 255, 255);
  $red = imageColorAllocate($img, 255, 0, 0);
	imagefilledrectangle($img,0,0,$nWidth,$nHeight,$white);
//  imageantialias($img,true);

  for ($n = 1; $n <= $nPPNs; ++$n)
  {
		$rad = (2*$PI*$n)/$nPPNs;
		$rad -= $PI/2; // start from top, not right
		
		$x1 = $xCentre + ($nBigRadius * cos($rad));
		$y1 = $yCentre + ($nBigRadius * sin($rad));

    for ($m = $n + 1; $m <= $nPPNs; ++$m)
    {
		  $rad = (2*$PI*$m)/$nPPNs;
		  $rad -= $PI/2; // start from top, not right
  		
		  $x2 = $xCentre + ($nBigRadius * cos($rad));
		  $y2 = $yCentre + ($nBigRadius * sin($rad));

      JoinCircles(
                $x1, $y1,
                $x2, $y2,
                $nSmallRadius + ($nPetalHeight/2.0),
                $img,
                $black);

      //
      // Draw petals
      //
      
      $xSize = $x2 - $x1;
      $ySize = $y2 - $y1;
  
      $dLength = sqrt(($xSize * $xSize) + ($ySize * $ySize));
      $dProportion = $nSmallRadius / $dLength;

      if ($xSize)
        $xAdjust = $xSize * $dProportion;
      else
        $xAdjust = 0;

      if ($ySize)
        $yAdjust = $ySize * $dProportion;
      else
        $yAdjust = 0;

      if ($xSize)
      {
        $radians = atan($ySize/$xSize) + $PI;
        $rotateangle = rad2deg(atan($ySize/$xSize) - $PI/2.0);
      }
      else
      {
        $radians = $PI;
        $rotateangle = rad2deg(0);
      }

      rotatedellipse($img,$x1 + $xAdjust,$y1 + $yAdjust,$nPetalWidth,$nPetalHeight,$rotateangle,$black);
      rotatedellipse($img,$x2 - $xAdjust,$y2 - $yAdjust,$nPetalWidth,$nPetalHeight,$rotateangle,$black);

      //
      // Figure out which session this connection occurs in
      //
      if (isset($sSession))
        unset($sSession);
      
      $sSearch = '(' . $n . '-' . $m . ')';
      for ($nSession = 0; $nSession < count($solution); ++$nSession)
      {
        $rc = strpos($solution[$nSession],$sSearch);
        if ($rc !== false)
          $sSession = $nSession + 1;
      }
      
      //
      // Write the text
      //
      $dProportion = $nTextRadius / $dLength;

      if ($xSize)
        $xAdjust = $xSize * $dProportion;
      else
        $xAdjust = 0;

      if ($ySize)
        $yAdjust = $ySize * $dProportion;
      else
        $yAdjust = 0;

      $bbox = imagettfbbox($nFontSize, 0, $font, $sSession);
      $nBoxWidth = abs($bbox[2] - $bbox[0]);
      $nBoxHeight = abs($bbox[7] - $bbox[1]);
 
      $xAdjust2 = - $nBoxWidth / 2.0;
      $yAdjust2 = $nBoxHeight / 2.0;

      if ($bSuffix)
      {
        WriteNumber($img, $nFontSize, $x1 + $xAdjust, $y1 + $yAdjust, $black, $font, $sSession);
        WriteNumber($img, $nFontSize, $x2 - $xAdjust, $y2 - $yAdjust, $black, $font, $sSession);
      }
      else
      {
        imagettftext($img, $nFontSize, 0, $x1 + $xAdjust + $xAdjust2, $y1 + $yAdjust + $yAdjust2, $black, $font, $sSession);
        imagettftext($img, $nFontSize, 0, $x2 - $xAdjust + $xAdjust2, $y2 - $yAdjust + $yAdjust2, $black, $font, $sSession);
      }
    }
  }

	for ($nPPN = 1; $nPPN <= $nPPNs; ++$nPPN)
	{
		$rad = (2*$PI*$nPPN)/$nPPNs;
		$rad -= $PI/2; // start from top, not right
		
		$x = $xCentre + ($nBigRadius * cos($rad));
		$y = $yCentre + ($nBigRadius * sin($rad));
		imagefilledellipse($img,$x,$y,$nSmallRadius*2,$nSmallRadius*2,$white);
		imageellipse($img,$x,$y,$nSmallRadius*2,$nSmallRadius*2,$black);
	}
  
	imagepng($img, $sFilename);
	imagedestroy($img);
}

function WriteNumber($img, $nFontSize, $x, $y, $colour, $font, $sText)
{
    switch($sText)
    {
    case 1:
      $sSuffix = "st";
      break;
    case 2:
      $sSuffix = "nd";
      break;
    case 3:
      $sSuffix = "rd";
      break;
    default:
      $sSuffix = "th";
    }

  $nFontSize2 = 2.0 * $nFontSize / 3.0;

  $bbox = imagettfbbox($nFontSize, 0, $font, $sText);
  $nNumberWidth = abs($bbox[2] - $bbox[0]);
  $nNumberHeight = abs($bbox[7] - $bbox[1]);

  $bbox = imagettfbbox($nFontSize2, 0, $font, $sSuffix);
  $nSuffixWidth = abs($bbox[2] - $bbox[0]);
  $nSuffixHeight = abs($bbox[7] - $bbox[1]);

  $xAdjust = - ($nNumberWidth + $nSuffixWidth) / 2.0;
  $yAdjust = $nNumberHeight / 2.0;

  imagettftext($img, $nFontSize, 0, $x + $xAdjust, $y + $yAdjust, $colour, $font, $sText);
  imagettftext($img, $nFontSize2, 0, $x + $xAdjust + $nNumberWidth, $y + $yAdjust, $colour, $font, $sSuffix);
}

function JoinCircles($x1,$y1,$x2,$y2,$radius,$img,$colour)
{
  $xSize = $x2 - $x1;
  $ySize = $y2 - $y1;
  
  $dLength = sqrt(($xSize * $xSize) + ($ySize * $ySize));
  $dProportion = $radius / $dLength;

  if ($xSize)
    $xAdjust = $xSize * $dProportion;
  else
    $xAdjust = 0;

  if ($ySize)
    $yAdjust = $ySize * $dProportion;
  else
    $yAdjust = 0;

  imageline($img,
            $x1 + $xAdjust, $y1 + $yAdjust,
            $x2 - $xAdjust, $y2 - $yAdjust,
            $colour);
}

function rotatedellipse($im, $cx, $cy, $width, $height, $rotateangle, $colour, $filled=false)
{
  // modified here from nojer's version
  // Rotates from the three o-clock position clockwise with increasing angle.
  // Arguments are compatible with imageellipse.

  $width=$width/2;
  $height=$height/2;

  // This affects how coarse the ellipse is drawn.
  $step=3;

  $cosangle=cos(deg2rad($rotateangle));
  $sinangle=sin(deg2rad($rotateangle));

  // $px and $py are initialised to values corresponding to $angle=0.
  $px=$width * $cosangle;
  $py=$width * $sinangle;
 
  for ($angle=$step; $angle<=(180+$step); $angle+=$step) {
   
    $ox = $width * cos(deg2rad($angle));
    $oy = $height * sin(deg2rad($angle));
   
    $x = ($ox * $cosangle) - ($oy * $sinangle);
    $y = ($ox * $sinangle) + ($oy * $cosangle);
 
    if ($filled) {
      triangle($im, $cx, $cy, $cx+$px, $cy+$py, $cx+$x, $cy+$y, $colour);
      triangle($im, $cx, $cy, $cx-$px, $cy-$py, $cx-$x, $cy-$y, $colour);
    } else {
      imageline($im, $cx+$px, $cy+$py, $cx+$x, $cy+$y, $colour);
      imageline($im, $cx-$px, $cy-$py, $cx-$x, $cy-$y, $colour);
    }
    $px=$x;
    $py=$y;
  }
}

function triangle($im, $x1,$y1, $x2,$y2, $x3,$y3, $colour)
{
   $coords = array($x1,$y1, $x2,$y2, $x3,$y3);
   imagefilledpolygon($im, $coords, 3, $colour);
}

?>