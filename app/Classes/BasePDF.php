<?php


namespace App\Classes;


class BasePDF
{
    protected $pdf;

    protected $view;

    protected $data;

    public function __construct()
    {
        $this->pdf = $this->pdfBuild();
    }

    static public function new()
    {
        return new BasePDF();
    }

    /**
     *
     * @param $view
     * @param $data
     * @return mixed
     */
    public function view($view, $data=[])
    {
        return $this->pdf->loadView($view, $data);
    }

    public function pdfBuild()
    {
        $pdf = \App::make('snappy.pdf.wrapper');

        $pdf->setOption('page-size', 'A4');
        $pdf->setOption('orientation', 'portrait');
        $pdf->setOption('encoding', 'utf-8');
        $pdf->setOption('dpi', 300);
        $pdf->setOption('image-dpi', 300);
        $pdf->setOption('lowquality', false);
        $pdf->setOption('no-background', false);
        $pdf->setOption('enable-internal-links', true);
        $pdf->setOption('enable-external-links', true);
        $pdf->setOption('javascript-delay', 1000);
        $pdf->setOption('no-stop-slow-scripts', true);
        $pdf->setOption('no-background', false);
        $pdf->setOption('margin-left', 5);
        $pdf->setOption('margin-right', 5);
        $pdf->setOption('margin-top', 35);
        $pdf->setOption('margin-bottom', 10);
        $pdf->setOption('disable-smart-shrinking', true);
        $pdf->setOption('zoom', 0.78);

        $this->pdf = $pdf;
        return $this;
    }
}
