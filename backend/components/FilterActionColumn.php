<?PHP
namespace backend\components;

use yii\grid\ActionColumn;

class FilterActionColumn extends ActionColumn
{
    public $filterContent;

    /**
     * Renders the filter cell content.
     * The default implementation simply renders a blank space.
     * This method may be overridden to customize the rendering of the filter cell (if any).
     * @return string the rendering result
     */
    protected function renderFilterCellContent()
    {
        return $this->filterContent;
    }
}
?>