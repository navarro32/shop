<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoriasRepository")
 */
class Categorias
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $codigo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activo;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Productos", mappedBy="categoria")
     */
    private $Categoria_id;

    public function __construct()
    {
        $this->Categoria_id = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCodigo(): ?string
    {
        return $this->codigo;
    }

    public function setCodigo(string $codigo): self
    {
        $this->codigo = $codigo;

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getActivo(): ?bool
    {
        return $this->activo;
    }

    public function setActivo(bool $activo): self
    {
        $this->activo = $activo;

        return $this;
    }

    /**
     * @return Collection|Productos[]
     */
    public function getCategoriaId(): Collection
    {
        return $this->Categoria_id;
    }

    public function addCategoriaId(Productos $categoriaId): self
    {
        if (!$this->Categoria_id->contains($categoriaId)) {
            $this->Categoria_id[] = $categoriaId;
            $categoriaId->setCategoria($this);
        }

        return $this;
    }

    public function removeCategoriaId(Productos $categoriaId): self
    {
        if ($this->Categoria_id->contains($categoriaId)) {
            $this->Categoria_id->removeElement($categoriaId);
            // set the owning side to null (unless already changed)
            if ($categoriaId->getCategoria() === $this) {
                $categoriaId->setCategoria(null);
            }
        }

        return $this;
    }
}
