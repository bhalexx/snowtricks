<?php

    namespace ST\SnowTricksBundle\Form\DataTransformer;

    use Symfony\Component\Form\DataTransformerInterface;
    use Symfony\Component\Form\Exception\TransformationFailedException;
    use Symfony\Component\HttpFoundation\File\UploadedFile;
    use ST\SnowTricksBundle\Entity\Picture;

    class PicturesTransformer implements DataTransformerInterface
    {
        /**
         * Returns pictures.
         *
         * @param   array $pictures
         * @return  array $pictures
         */
        public function transform($pictures)
        {
            return $pictures;
        }

        /**
         * Transforms each pictures item to an object (Picture).
         *
         * @param  array $pictures
         * @return array
         */
        public function reverseTransform($pictures)
        {
            $photos = [];
            foreach ($pictures as $picture) {
                $photo = new Picture();
                $photo->setFile($picture);
                $photos[] = $photo;
            }
            return $photos;
        }
    }