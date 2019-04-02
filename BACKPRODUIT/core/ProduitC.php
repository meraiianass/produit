<?php 
include_once '../config2.php';
class ProduitC{
public function ajouterProduit($produit)
	{
		try{
			$it = $produit->getImageTmp();
			$i = $produit->getImage();
			//move the uploaded pictures
			move_uploaded_file($it,"../uploads/$i");
			
			$c = new Config();
			$driver = $c->getConnexion();
			$stmt = $driver->prepare('INSERT INTO produit(nom, prix, description, image, quantite,reference,categorie) 
									  VALUES (:n,:p,:d,:i,:q,:r,:c)');
			$n = $produit->getNom();
			$c=$produit->getCategorie();
			$p = $produit->getPrix();
			$d = $produit->getDescription();
			$i = $produit->getImage();
			$q = $produit->getQuantite();
			$r = $produit->getReference();
			$stmt->bindparam(':n', $n);
			$stmt->bindparam(':c', $c);
			$stmt->bindparam(':p', $p);
			$stmt->bindparam(':d', $d);
			$stmt->bindparam(':i', $i);
	/*		$stmt->bindparam(':c',$this->categorie);
			$stmt->bindparam(':sc',$this->sous_cat);*/
			$stmt->bindparam(':q', $q);
			$stmt->bindparam(':r', $r);

			$stmt->execute();

		}catch(PDOException $ex){
			echo "Erreur: ".$ex->getMessage();
		}
	}

	public function   afficherProduit()
	{
		try{

			$c = new Config();
			$driver = $c->getConnexion();
			$stmt = $driver->prepare('SELECT `nom`, `prix`, `description`, `image`, `quantite`, `reference`, `id`, `nom_categorie`,`categorie` FROM `produit` inner join `categorie` on `produit`.categorie=`categorie`.id_categorie ');
			$stmt->execute();
			$produits=$stmt->fetchAll();
			//var_dump($produits);
			return $produits;

		}catch(PDOException $ex){
			echo "Erreur: ".$ex->getMessage();
		}

	}

	public function afficherProduitParId($id){
		try{

			$c = new Config();
			$driver = $c->getConnexion();
			$stmt = $driver->prepare('SELECT * FROM produit WHERE id = :id');
			$stmt->bindparam(":id",$id);
			$stmt->execute();
		 	return $stmt->fetch(PDO::FETCH_ASSOC);

		}catch(PDOException $ex){
			echo "Erreur: ".$ex->getMessage();
		}
	}

	public function afficherProduitParNom($nom,$prix,$categorie){
		try{

			$c = new Config();
			$driver = $c->getConnexion();
			$stmt = $driver->prepare('SELECT * FROM produit WHERE nom = :nom and prix= :prix and categorie=:categorie ');
			$stmt->bindparam(":nom",$nom);
			$stmt->bindparam(":prix",$prix);
			$stmt->bindparam(":categorie",$categorie);
			$stmt->execute();
		 	return $stmt->fetch(PDO::FETCH_ASSOC);

		}catch(PDOException $ex){
			echo "Erreur: ".$ex->getMessage();
		}
	}

	public function supprimerProduit($id){
		try{

			$c = new Config();
			$driver = $c->getConnexion();
			$stmt = $driver->prepare('DELETE FROM produit WHERE id = :id');
			$stmt->bindparam(":id",$id);
			if($stmt->execute())
				header('location: afficherproduit.php');

		}catch(PDOException $ex){
			echo "Erreur: ".$ex->getMessage();
		}
	}

	public function ModifierProduit($produit){
		try{
			$n = $produit->getNom();
			$c = $produit->getCategorie();
			$p = $produit->getPrix();
			$d = $produit->getDescription();
			$i = $produit->getId();
			$q = $produit->getQuantite();
			$r = $produit->getReference();

			$c = new Config();
			$driver = $c->getConnexion();
			$stmt = $driver->prepare('UPDATE produit SET nom = :n,prix = :p, description = :d, quantite = :q ,reference = :r where  id = :id');
			
			$stmt->bindparam(':n', $n);
			$stmt->bindparam(':p', $p);
			$stmt->bindparam(':d', $d);
			$stmt->bindparam(':q', $q);
			$stmt->bindparam(':r', $r);
		//	$stmt->bindparam(':c', $c);
			$stmt->bindparam(':id', $i);
			if($stmt->execute())
				header('location: afficherproduit.php');

		}catch(PDOException $ex){
			echo "Erreur: ".$ex->getMessage();
		}
	}
	public function chercherProduit($nom){
		$c = new Config();
		$driver = $c->getConnexion();
		$stmt = $driver->prepare("SELECT * FROM produit WHERE  nom LIKE:n");
		$stmt->bindparam(':n', $n);
	
		$stmt->execute();
		//var_dump($liste);
		$produits=$stmt->fetchAll();
			//var_dump($produits);
			return $produits;
	}



	
 function ajouterTrie($product_id,$moyenne)
	{
		try{
			
			$c = new Config();
			$driver = $c->getConnexion();
			$stmt = $driver->prepare('INSERT INTO trie(product_id, moyenne ) 
									  VALUES (:p,:m)');
			
		
			$stmt->bindparam(':p', $product_id);
			$stmt->bindparam(':m', $moyenne);
		

			$stmt->execute();

		}catch(PDOException $ex){
			echo "Erreur: ".$ex->getMessage();
		}
	}
	function affichPiecetripopulaire(){
		$sql="SElECT p.* from produit  p inner join  trie t on t.product_id=p.id  order by t.moyenne desc";
		$db = config::getConnexion();
		try{
		$liste=$db->query($sql);
		return $liste;
		}
        catch (Exception $e){
            die('Erreur: '.$e->getMessage());
        }}	
         function count($id){
		try{
			$c =new Config();
			$driver = $c->getConnexion();
			$stmt = $driver->prepare('SELECT COUNT(vote) as count FROM rating WHERE product_id=:id');
			$stmt->bindparam(":id",$id);
			$stmt->execute();
		 	$l=$stmt->fetchAll(PDO::FETCH_ASSOC);
		 	return $l;
		}catch(PDOException $ex){
			echo "Erreur: ".$ex->getMessage();

	}}	
	 function countstat($id,$inf,$sup){
		try{
			$c =new Config();
			$driver = $c->getConnexion();
			$stmt = $driver->prepare('SELECT COUNT(vote) as count FROM rating WHERE product_id=:id and ( vote<=:sup and vote>:inf)');
			$stmt->bindparam(":id",$id);
				$stmt->bindparam(":inf",$inf);
					$stmt->bindparam(":sup",$sup);
			$stmt->execute();
		 	$l=$stmt->fetchAll(PDO::FETCH_ASSOC);
		 	return $l;
		}catch(PDOException $ex){
			echo "Erreur: ".$ex->getMessage();

	}}



	
	public function afficherTrie()
	{
		try{

			$c = new Config();
			$driver = $c->getConnexion();
			$stmt = $driver->prepare('SELECT * FROM categorie ');
			$stmt->execute();
			$trie=$stmt->fetchAll();
			//var_dump($produits);
			return $trie;

		}catch(PDOException $ex){
			echo "Erreur: ".$ex->getMessage();
		}

	}


public function afficherAllProducts(){
		try{
			$c = new Config();
			$driver = $c->getConnexion();
			$stmt = $driver->prepare('SELECT * FROM produit ');
			$stmt->execute();
		 	$l=$stmt->fetchAll(PDO::FETCH_ASSOC);
		 	return $l;
		}catch(PDOException $ex){
			echo "Erreur: ".$ex->getMessage();
		}
	}
	public function trierProduit($id){
		try{
			$c =new Config();
			$driver = $c->getConnexion();
			$stmt = $driver->prepare('SELECT product_id as id,(SUM(vote)/COUNT(vote)) as moyenne FROM rating WHERE product_id=:id');
			$stmt->bindparam(":id",$id);
			$stmt->execute();
		 	$l=$stmt->fetchAll(PDO::FETCH_ASSOC);
		 	return $l;
		}catch(PDOException $ex){
			echo "Erreur: ".$ex->getMessage();

	}

}

public function afficherCategorie()
	{
		try{

			$c = new Config();
			$driver = $c->getConnexion();
			$stmt = $driver->prepare('SELECT * FROM categorie ');
			$stmt->execute();
			$categorie=$stmt->fetchAll();
			//var_dump($produits);
			return $categorie;

		}catch(PDOException $ex){
			echo "Erreur: ".$ex->getMessage();
		}

	}

}

 ?>