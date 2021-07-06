<?php echo '<?php'; ?>

namespace <?php echo $namespace; ?>;

class <?php echo $className; ?>

{
<?php foreach ($properties as $property) { ?>
    /**
     * @var <?php echo $property['paramType']; ?>

     */
    private $<?php echo $property['varName']; ?>;

<?php } ?>
    /**
<?php foreach ($properties as $property) { ?>
     * @param <?php echo $property['paramType']; ?>  $<?php echo $property['varName']; ?>;
<?php } ?>
     */
    public function __construct(<?php foreach ($properties as $i => $property) { ?><?php echo $property['varType']; ?> $<?php echo $property['varName']; ?><?php if ($i < count($properties) - 1) { ?>, <?php } else { ?><?php } ?><?php } ?>)
    {
<?php foreach ($properties as $property) { ?>
         $this-><?php echo $property['varName']; ?> = $<?php echo $property['varName']; ?>;
<?php } ?>
    }
<?php foreach ($properties as $property) { ?>

    /**
     * @return <?php echo $property['paramType']; ?>

     */
    public function get<?php echo $property['methodName']; ?>()<?php echo empty($property['varType']) ? '' : ' :'.$property['varType']; ?>

    {
        return $this-><?php echo $property['varName']; ?>;
    }
<?php } ?>

    public static function builder(): <?php echo $builder['shortName']; ?>

    {
        return new <?php echo $builder['shortName']; ?>();
    }
}
