<?
class MC_TABLE extends FPDF {
	var $widths;
	var $aligns;
	
	function SetWidths($w) {
		//Set the array of column widths
		$this->widths=$w;
	}
		
	function SetAligns($a) {
		//Set the array of column alignments
		$this->aligns=$a;
	}
		
	function Row($data,$rowheight,$borderStyle) {
		//Calculate the height of the row
		$nb=0;
		for($i=0;$i<count($data);$i++)
			$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
		$h=$rowheight*$nb;
		//Issue a page break first if needed
		$this->CheckPageBreak($h);
		//Draw the cells of the row
		
		$xpos=$this->setxpos;
		$this->setX($xpos);
		for($i=0;$i<count($data);$i++)
		{
			$w=$this->widths[$i];
			$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
			//Save the current position
			$x=$this->GetX();
			$y=$this->GetY();
			//Draw the border
			/*$this->Rect($x,$y,$w,$h);*/
			//Print the text
			$this->MultiCell($w,$rowheight,$data[$i],$borderStyle,$a);
			//Put the position to the right of the cell
			$this->SetXY($x+$w,$y);
		}
		//Go to the next line
		$this->Ln($h);
	}

	function SetFontsWeight($fw) {
		//Set the array of column alignments
		$this->fontsweight=$fw;
	}

	function SetFontsSize($fs) {
		//Set the array of column alignments
		$this->fontssize=$fs;
	}
		
	function RowWithBorder($data,$rowheight,$borderStyle) {
		//Calculate the height of the row
		$nb=0;
		for($i=0;$i<count($data);$i++)
			$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
		$h=$rowheight*$nb;
		/*if($h > 5 && $h < 10) {
			$h=$rowheight*($nb-1);
		} elseif($h >= 10) {
			$h=$rowheight*($nb-2);
		} elseif($h==5) {
			$h=$h;	
		}*/
		
		//Issue a page break first if needed
		$this->CheckPageBreak($h);
		
		//Draw the cells of the row
		$xpos=$this->setxpos;
		$this->setX($xpos);
		for($i=0;$i<count($data);$i++)
		{
			$w=$this->widths[$i];
			$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
			//Save the current position
			$x=$this->GetX();
			$y=$this->GetY();
			//Draw the border
			$this->Rect($x,$y,$w,$h);
			//Set Font
			$this->SetFont('Times',$this->fontsweight[$i],$this->fontssize[$i]);
			//Print the text
			$this->MultiCell($w,$rowheight,$data[$i],0,$a);
			//Put the position to the right of the cell
			$this->SetXY($x+$w,$y);
		}
		//Go to the next line
		$this->Ln($h);
	}
		
	function CheckLastPos($LastPos) {
		return $LastPos;
	}	
		
	function CheckPageBreak($h) {
		//If the height h would cause an overflow, add a new page immediately
		if($this->GetY()+$h>$this->PageBreakTrigger)
			$this->AddPage($this->CurOrientation);
	}
	
	function NbLines($w,$txt) {
		//Computes the number of lines a MultiCell of width w will take
		$cw=&$this->CurrentFont['cw'];
		if($w==0)
		$w=$this->w-$this->rMargin-$this->x;
		$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
		$s=str_replace("\r",'',$txt);
		$nb=strlen($s);
		if($nb>0 and $s[$nb-1]=="\n")
			$nb--;
		$sep=-1;
		$i=0;
		$j=0;
		$l=0;
		$nl=1;
		while($i<$nb)
		{
			$c=$s[$i];
			if($c=="\n")
			{
				$i++;
				$sep=-1;
				$j=$i;
				$l=0;
				$nl++;
				continue;
			}
			if($c==' ')
				$sep=$i;
			$l+=$cw[$c];
			if($l>$wmax)
			{
				if($sep==-1)
				{
					if($i==$j)
						$i++;
				}
				else
					$i=$sep+1;
				$sep=-1;
				$j=$i;
				$l=0;
				$nl++;
			}
			else
				$i++;
		}
		return $nl;
	}
	
	function Header() {
		global $HeaderNote; global $HeaderType;
		global $PageType;
		global $nolab;
		global $DOB;
		global $Age;
		global $NewBirthDate;
		
		if($PageType=="DETAIL") {
			$this->SetFont('Times','B',14);
			$this->SetFillColor(180,180,180);
			$this->SetTextColor(0,0,0);
	
			global $Name; global $PasienName;
			global $Sex; global $SexDesc;
			global $MR_Detail; global $rekammedis;
			global $Comp; global $CompanyID; global $CompanyName;
			global $Visit; global $tregistrasi; global $EmployeeNo;
			
			if($HeaderType=="LAB") {
				$this->SetY(12); $this->SetX(12);
				$this->Cell(201,7,$HeaderNote,1,1,'C',1);

				$this->Ln(1); 
				$this->SetFillColor(255,255,255);
				$this->SetFont('Times','B',9.5);
				$this->SetX(12); $this->Cell(105,6,'',1,0,'L',0);
				$this->SetX(12); $this->Cell(10,6,$Name,0,0,'L',0);
				$this->SetFont('Times','',9.5);
				$this->Cell(70,6,': '.strtoupper($PasienName),0,0,'L',0);
				
				$this->SetX(118); $this->Cell(27,6,'',1,0,'L',0); 
				$this->SetFont('Times','B',9.5);
				$this->SetX(118); $this->Cell(8,6,'',0,0,'L'); 
				$this->SetX(118); $this->Cell(7,6,$MR_Detail,0,0,'L'); 
				$this->SetFont('Times','',9.5);
				$this->Cell(15,6,': '.$rekammedis,0,0,'L',0); 
		
				$this->SetFont('Times','B',9.5);
				$this->SetX(146); $this->Cell(31,6,'',1,0,'L',0); 
				$this->SetX(146); $this->Cell(12,6,$Visit,0,0,'L',0); 
				$this->SetFont('Times','',9.5);
				$this->Cell(33,6,': '.$tregistrasi,0,0,'L',0);
				
				$this->SetFont('Times','B',9.5);
				$this->SetX(178); $this->Cell(35,6,'',1,0,'L',0); 
				$this->SetX(178); $this->Cell(8,6,'#Lab',0,0,'L',0); 
				$this->SetFont('Times','',9.5);
				$this->Cell(21,6,': '.$nolab,0,1,'L',0);
				
				$this->Ln(1); 
				$this->SetFont('Times','B',9.5);
				$this->SetX(12); $this->Cell(105,6,'',1,0,'L',0); 
				$this->SetX(12); $this->Cell(10,6,$Comp,0,0,'L',0); 
				$this->SetFont('Times','',9.5);
				$this->Cell(70,6,': '.$CompanyName,0,0,'L',0);

				$this->SetX(118); $this->Cell(27,6,'',1,0,'L',0); 
				$this->SetFont('Times','B',9.5);
				$this->SetX(118); $this->Cell(8,6,'',0,0,'L'); 
				$this->SetX(118); $this->Cell(7,6,$Sex,0,0,'L'); 
				$this->SetFont('Times','',9.5);
				$this->Cell(15,6,': '.$SexDesc,0,0,'L',0); 

				$this->SetFont('Times','B',9.5);
				$this->SetX(146); $this->Cell(67,6,'',1,0,'L',0); 
				$this->SetX(146); $this->Cell(12,6,$DOB,0,0,'L',0); 
				$this->SetFont('Times','',9.5);
				$this->Cell(70,6,': '.$NewBirthDate,0,1,'L',0);
			} elseif($HeaderType=="VITALSIGN") {
				$this->SetY(40); $this->SetX(12); 
				$this->Cell(199,7,$HeaderNote,1,1,'C',1);

				$this->Ln(1); 
				$this->SetFillColor(255,255,255);
				$this->SetFont('Times','B',9.5);
				$this->SetX(12); $this->Cell(106,6,'',1,0,'L',0);
				$this->SetX(12); $this->Cell(10,6,$Name,0,0,'L',0);
				$this->SetFont('Times','',9.5);
				$this->Cell(70,6,': '.strtoupper($PasienName),0,0,'L',0);
				
				$this->SetX(119); $this->Cell(27,6,'',1,0,'L',0); 
				$this->SetFont('Times','B',9.5);
				$this->SetX(119); $this->Cell(8,6,'',0,0,'L'); 
				$this->SetX(119); $this->Cell(7,6,$Sex,0,0,'L'); 
				$this->SetFont('Times','',9.5);
				$this->Cell(15,6,': '.$SexDesc,0,0,'L',0); 
		
				$this->SetFont('Times','B',9.5);
				$this->SetX(147); $this->Cell(32,6,'',1,0,'L',0); 
				$this->SetX(147); $this->Cell(12,6,$MR_Detail,0,0,'L',0); 
				$this->SetFont('Times','',9.5);
				$this->Cell(33,6,': '.$rekammedis,0,0,'L',0);
					
				$this->SetFont('Times','B',9.5);
				$this->SetX(180); $this->Cell(31,6,'',1,0,'L',0); 
				$this->SetX(180); $this->Cell(12,6,$Visit,0,0,'L',0); 
				$this->SetFont('Times','',9.5);
				$this->Cell(21,6,': '.$tregistrasi,0,1,'L',0);
				
				$this->Ln(1); 
				$this->SetFont('Times','B',9.5);
				$this->SetX(12); $this->Cell(106,6,'',1,0,'L',0); 
				$this->SetX(12); $this->Cell(10,6,$Comp,0,0,'L',0); 
				$this->SetFont('Times','',9.5);
				$this->Cell(70,6,': '.$CompanyName,0,0,'L',0);

				$this->SetX(119); $this->Cell(27,6,'',1,0,'L',0); 
				$this->SetFont('Times','B',9.5);
				$this->SetX(119); $this->Cell(8,6,'',0,0,'L'); 
				$this->SetX(119); $this->Cell(7,6,'NIK',0,0,'L'); 
				$this->SetFont('Times','',9.5);
				$this->Cell(15,6,': '.$EmployeeNo,0,0,'L',0); 

				$this->SetFont('Times','B',9.5);
				$this->SetX(147); $this->Cell(64,6,'',1,0,'L',0); 
				$this->SetX(147); $this->Cell(12,6,$DOB,0,0,'L',0); 
				$this->SetFont('Times','',9.5);
				$this->Cell(70,6,': '.$NewBirthDate.' ('.$Age.' Thn)',0,1,'L',0);
			} else {
				if($CompanyID=="T83143d") { 
					$this->SetY(40); $this->SetX(12); 
					$this->Cell(199,7,$HeaderNote,1,1,'C',1); 
				} else { 
					$this->SetY(12); $this->SetX(12); 
					$this->Cell(201,7,$HeaderNote,1,1,'C',1); 
				}

				$this->Ln(1); 
				$this->SetFillColor(255,255,255);
				$this->SetFont('Times','B',9.5);
				$this->SetX(12); $this->Cell(106,6,'',1,0,'L',0);
				$this->SetX(12); $this->Cell(10,6,$Name,0,0,'L',0);
				$this->SetFont('Times','',9.5);
				$this->Cell(70,6,': '.strtoupper($PasienName),0,0,'L',0);
				
				$this->SetX(119); $this->Cell(27,6,'',1,0,'L',0); 
				$this->SetFont('Times','B',9.5);
				$this->SetX(119); $this->Cell(8,6,'',0,0,'L'); 
				$this->SetX(119); $this->Cell(7,6,$Sex,0,0,'L'); 
				$this->SetFont('Times','',9.5);
				$this->Cell(15,6,': '.$SexDesc,0,0,'L',0); 
		
				$this->SetFont('Times','B',9.5);
				$this->SetX(147); $this->Cell(32,6,'',1,0,'L',0); 
				$this->SetX(147); $this->Cell(12,6,$MR_Detail,0,0,'L',0); 
				$this->SetFont('Times','',9.5);
				$this->Cell(33,6,': '.$rekammedis,0,0,'L',0);
					
				$this->SetFont('Times','B',9.5);
				$this->SetX(180); if($CompanyID=="T83143d") { $this->Cell(31,6,'',1,0,'L',0); } else { $this->Cell(33,6,'',1,0,'L',0); }
				$this->SetX(180); $this->Cell(12,6,$Visit,0,0,'L',0); 
				$this->SetFont('Times','',9.5);
				$this->Cell(21,6,': '.$tregistrasi,0,1,'L',0);
				
				$this->Ln(1); 
				$this->SetFont('Times','B',9.5);
				$this->SetX(12); 
				if($CompanyID=="T83143d") { $this->Cell(106,6,'',1,0,'L',0); } else { $this->Cell(134,6,'',1,0,'L',0); }
				$this->SetX(12); $this->Cell(10,6,$Comp,0,0,'L',0); 
				$this->SetFont('Times','',9.5);
				$this->Cell(86,6,': '.$CompanyName,0,0,'L',0);

				if($CompanyID=="T83143d") {
					$this->SetX(119); $this->Cell(27,6,'',1,0,'L',0); 
					$this->SetFont('Times','B',9.5);
					$this->SetX(119); $this->Cell(8,6,'',0,0,'L'); 
					$this->SetX(119); $this->Cell(7,6,'NIK',0,0,'L'); 
					$this->SetFont('Times','',9.5);
					$this->Cell(15,6,': '.$EmployeeNo,0,0,'L',0); 
				}

				$this->SetFont('Times','B',9.5);
				$this->SetX(147); if($CompanyID=="T83143d") { $this->Cell(64,6,'',1,0,'L',0); } else { $this->Cell(66,6,'',1,0,'L',0); }
				$this->SetX(147); $this->Cell(12,6,$DOB,0,0,'L',0); 
				$this->SetFont('Times','',9.5);
				$this->Cell(70,6,': '.$NewBirthDate,0,1,'L',0); 
			}
	
			global $ExamHeader;
			global $ResultHeader;
			global $NotesHeader;
			global $NormalRangeHeader;
			global $HeaderType;
			global $Comment;
			$this->Ln(1); 
			$this->SetFillColor(180,180,180);
			$this->SetTextColor(0,0,0);
			$this->SetFont('Times','B',9.5);
			$this->SetX(12);
			if($HeaderType=="FISIK") {
				$this->Cell(90,6,$ExamHeader,1,0,'C',1);
				$this->Cell(40,6,$ResultHeader,1,0,'C',1);
				$this->Cell(71,6,$NotesHeader,1,1,'C',1);
			} elseif($HeaderType=="LAB") {
				$this->Cell(90,6,$ExamHeader,1,0,'C',1);
				$this->Cell(40,6,$ResultHeader,1,0,'C',1);
				$this->Cell(71,6,$NormalRangeHeader,1,1,'C',1);
			} elseif($HeaderType=="LAB-PAPS") {
				$this->Cell(90,6,$ExamHeader,1,0,'C',1);
				$this->Cell(111,6,$ResultHeader,1,1,'C',1);
			} elseif($HeaderType=="MATA") {
				global $RightEye;
				global $LeftEye;
				$this->Cell(89,6,$ExamHeader,1,0,'C',1);
				$this->Cell(56,6,$RightEye,1,0,'C',1);
				$this->Cell(56,6,$LeftEye,1,0,'C',1);
			} elseif($HeaderType=="AUDIO") {
				$this->Cell(201,6,$ResultHeader,1,0,'C',1);
			} elseif($HeaderType=="JANTUNG") {
				$this->Cell(60,6,$ExamHeader,1,0,'C',1);
				$this->Cell(78,6,$ResultHeader,1,0,'C',1);
				if($CompanyID=="T83143d") { $this->Cell(61,6,$Comment,1,1,'C',1); } else { $this->Cell(63,6,$Comment,1,1,'C',1); }
			} elseif($HeaderType=="PARU") {
				$this->Cell(60,6,$ExamHeader,1,0,'C',1);
				$this->Cell(141,6,$ResultHeader,1,0,'C',1);
			} elseif($HeaderType=="RAD") {
				$this->Cell(60,6,$ExamHeader,1,0,'C',1);
				if($CompanyID=="T83143d") { $this->Cell(139,6,$ResultHeader,1,1,'C',1); } else { $this->Cell(141,6,$ResultHeader,1,1,'C',1); }
			} elseif($HeaderType=="RAD-FILM") {
				$this->Cell(201,1,'',1,1,'C',1);
			} elseif($HeaderType=="USG-FILM") {
				$this->Cell(201,1,'',1,1,'C',1);
			}
	
			$this->ln(2);
		}
	}

	function Footer() {
		$currnum=$this->page-3;

		if($this->footerstatus=="AKTIF") {
			global $PrintedBy;
			global $userLogin;
			global $VerifiedBy;
			global $DokterFisik;
			global $DokterLab;
			global $DokterMata;
			global $DokterAudio;

			global $KodeDokterCardio;
			global $DokterCardio;
			
			global $KodeDokterRadiologi;
			global $DokterRadiologi;
			
			global $DokterSpiro;
			global $CompanyID;
			
			if($this->footerid=="SERTIFIKAT") {
				$style = array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => '0', 'phase' => 5, 'color' => array(0, 0, 0));
				
				$this->SetY(-16);
				$this->SetTextColor(0,79,123);
				$this->SetFont('helvetica','B',8);
				$CurrY=$this->GetY();
				$this->Line(28,$CurrY-1,204,$CurrY-1,$style);
				$this->SetX(28); $this->Cell(176,4,'PT. KARTIKA BINA MEDIKATAMA',0,1,'C');
				$this->SetTextColor(0,0,0);
				$this->SetFont('arial','',4);
				$this->SetX(50); $this->Cell(6,4,'OFFICE',0,0,'L');
				$this->SetFont('arial','',6);
				$this->Cell(68,4,'Menara Kuningan 5th Fl. Jl. HR. Rasuna Said X7 Kav.5, Jakarta 12940',0,0,'L');
				$this->SetFont('arial','',4); $this->Cell(4,4,'TEL',0,0,'L');
				$this->SetFont('arial','',6); $this->Cell(19,4,'+62 21 3002 7070',0,0,'L');
				$this->SetFont('arial','',4); $this->Cell(11,4,'HUNTING. FAX',0,0,'L');
				$this->SetFont('arial','',6); $this->Cell(18,4,'+62 21 3002 7065',0,1,'L');
				$this->SetFont('arial','',4);
				$this->SetX(46); $this->Cell(6,4,'CLINIC',0,0,'L');
				$this->SetFont('arial','',6);
				$this->Cell(78,4,'Menara Kartika Chandra 3rd Fl. Jl. Jend. Gatot Subroto Kav.18-20, Jakarta 12060',0,0,'L');
				$this->SetFont('arial','',4); $this->Cell(4,4,'TEL',0,0,'L');
				$this->SetFont('arial','',6); $this->Cell(18,4,'+62 21 525 1207',0,0,'L');
				$this->SetFont('arial','',4); $this->Cell(11,4,'HUNTING. FAX',0,0,'L');
				$this->SetFont('arial','',6); $this->Cell(18,4,'+62 21 521 0815',0,1,'L');

				$this->SetFont('arial','',6);
				$this->SetX(28); $this->Cell(174,4,'www.medikaplaza.com',0,0,'C');

			} elseif($this->footerid=="FRONT-PAGE") {
				$this->SetY(-7);
				$this->SetFont('Times','I',9);
				$CurrY=$this->GetY();
				$this->Line(28,$CurrY-1,192,$CurrY-1,$style);
				$this->SetX(28); $this->SetFont('Times','B',11); $this->Cell(164,4,'PRIVATE',0,0,'C');
			} else {
				if($this->footerid=="FISIK") {
					$showVerifiedDoc='Verified by : dr.'.$DokterFisik;
				} elseif($this->footerid=="LAB") {
					$showVerifiedDoc='Verified by : dr.'.$DokterLab;
				} elseif($this->footerid=="MATA") {
					$showVerifiedDoc='Verified by : dr.'.$DokterMata;
				} elseif($this->footerid=="AUDIO") {
					$showVerifiedDoc='Verified by : '.$DokterAudio;
				} elseif($this->footerid=="CARDIO") {
					if($KodeDokterCardio=="D0020007") {
						$this->Image('../ttd/dradolf.jpg',25,238,26,30);
					}
					$showVerifiedDoc='Verified by : dr.'.$DokterCardio;
				} elseif($this->footerid=="RADIOLOGI") {
					if($KodeDokterRadiologi=="D0020013") {
						$this->Image('../ttd/drreno.jpg',20,240,30,30);
					} elseif($KodeDokterRadiologi=="D0020145") { 
						$this->Image('../ttd/drbulan.jpg',20,248,20,20);
					} elseif($KodeDokterRadiologi=="5849.D178") { 
						$this->Image('../ttd/drary.jpg',20,248,20,20);
					} elseif($KodeDokterRadiologi=="D0020014") { //dr.Wayan
						$this->Image('../ttd/drwayan.jpg',20,248,20,20);
					} elseif($KodeDokterRadiologi=="D0020149") { //dr.Sugianto 
						$this->Image('../ttd/drsugianto.jpg',20,248,20,20);
					} elseif($KodeDokterRadiologi=="2ac8.D185") { //dr.Natali
						$this->Image('../ttd/drnatali.jpg',20,248,20,20);
					}
					$showVerifiedDoc='Verified by : dr.'.$DokterRadiologi;
				} elseif($this->footerid=="SPIRO") {
					$showVerifiedDoc='Verified by : dr.'.$DokterSpiro;
				} elseif($this->footerid=="SUMMARY") {
					$set_docname="";		
					$showVerifiedDoc="";
				}
		
				$style = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => '0', 'phase' => 5, 'color' => array(0, 0, 0));
				
				if($CompanyID!="T83143d") {
					$this->SetY(-7);
					$this->SetFont('Times','I',9);
					$CurrY=$this->GetY();
					$this->Line(12,$CurrY-1,212,$CurrY-1,$style);	
					$this->SetFont('Times','I',9); $this->SetX(12); $this->Cell(80,4,$showVerifiedDoc,0,0,'L');
					$this->SetFont('Times','B',11); $this->Cell(40,4,'PRIVATE',0,0,'C');
					$this->SetFont('Times','I',9); $this->Cell(80,4,$PrintedBy.' : '.$userLogin,0,0,'R');
				} else {
					$this->SetY(-10);
					$this->SetFont('Times','I',9);
					$this->SetFont('Times','IU',9); $this->SetX(12); $this->Cell(80,4,$showVerifiedDoc,0,1,'L');
					$this->SetFont('Times','',9); $this->SetX(12); $this->Cell(80,4,'Medika Plaza',0,0,'L');	
				}
			}
		}
	}
}


//Create new pdf file
$pdf = new MC_TABLE(P,mm,letter);
//Open file
$pdf->Open();

//Disable automatic page break
$pdf->SetAutoPageBreak(true,10);
$pdf->SetRightMargin(4);
?>